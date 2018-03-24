<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\payment\gateways;

use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\httpclient\Client;
use yuncms\payment\exceptions\PaymentException;
use yuncms\web\Request;
use yuncms\base\HasHttpRequest;
use yuncms\payment\contracts\ChargeInterface;

/**
 * Class Wechat
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class WeChat extends Gateway
{
    use HasHttpRequest;

    const SIGNATURE_METHOD_MD5 = 'MD5';
    const SIGNATURE_METHOD_SHA256 = 'HMAC-SHA256';

    /**
     * @var string 网关地址
     */
    public $baseUrl = 'https://api.mch.weixin.qq.com';

    /**
     * @var string 绑定支付的开放平台 APPID
     */
    public $appId;

    /**
     * @var string 商户支付密钥
     * @see https://pay.weixin.qq.com/index.php/core/cert/api_cert
     */
    public $apiKey;

    /**
     * @var string 商户号
     * @see https://pay.weixin.qq.com/index.php/core/account/info
     */
    public $mchId;

    /**
     * @var string 私钥
     */
    public $privateKey;

    /**
     * @var string 公钥
     */
    public $publicKey;

    /**
     * @var string 签名方法
     */
    public $signType = self::SIGNATURE_METHOD_SHA256;

    /**
     * @var array 交易类型和Trade映射
     */
    public $tradeTypeMap = [
        self::TRADE_TYPE_QR_CODE => 'NATIVE',//WEB 原生扫码支付
        self::TRADE_TYPE_JS_API => 'JSAPI',//应用内JS API,如微信
        self::TRADE_TYPE_APP => 'APP',//app支付
        self::TRADE_TYPE_WAP => 'MWEB',//H5支付
        self::TRADE_TYPE_POS => 'MICROPAY',//刷卡支付
        self::TRADE_TYPE_WEB => 'NATIVE'//PC支付
    ];

    /**
     * 初始化
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        if (!in_array('sha256', openssl_get_md_methods(), true)) {
            trigger_error('need openssl support sha256', E_USER_ERROR);
        }
        if (empty ($this->appId)) {
            throw new InvalidConfigException ('The "appId" property must be set.');
        }
        if (empty ($this->apiKey)) {
            throw new InvalidConfigException ('The "apiKey" property must be set.');
        }
        if (empty ($this->mchId)) {
            throw new InvalidConfigException ('The "mchId" property must be set.');
        }
        if (empty ($this->privateKey)) {
            throw new InvalidConfigException ('The "privateKey" property must be set.');
        }
        if (empty ($this->publicKey)) {
            throw new InvalidConfigException ('The "publicKey" property must be set.');
        }
        $this->initPrivateKey();
        $this->initPublicKey();
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return Yii::t('yuncms', 'WeChat Pay');
    }

    /**
     * @param ChargeInterface $charge
     * @return
     * @throws PaymentException
     * @throws Exception
     */
    public function Charge(ChargeInterface $charge)
    {
        $data = [
            'body' => $charge->getSubject(),
            'out_trade_no' => $charge->getOrderNo(),
            'total_fee' => $charge->getAmount(),
            'fee_type' => $charge->getCurrency(),
            'trade_type' => $this->getTradeType($charge->type),
            'notify_url' => $this->getNoticeUrl(),
            'spbill_create_ip' =>$charge->getClientIp(),
            'device_info' => 'WEB',
            'attach' => $charge->getDescription(),
        ];
        if ($charge->type == self::TRADE_TYPE_JS_API) {
            if (isset($trade->user->socialAccounts['wechat'])) {
                $weParams = $trade->user->socialAccounts['wechat']->getDecodedData();
                $data['openid'] = $weParams['openid'];
            } else {
                throw new PaymentException ('Non-WeChat authorized login.');
            }
        }
        $response = $this->post('pay/unifiedorder', $data)->send();
        if ($response->isOk) {
            if ($response->data['return_code'] == 'SUCCESS') {
                $trade->updateAttributes(['pay_id' => $response->data['prepay_id']]);
                return $response->data;
            } else {
                throw new PaymentException($response->data['return_msg']);
            }
        } else {
            throw new Exception ('Http request failed.');
        }
    }

    /**
     * 统一下单(公众号，扫码，APP，刷卡等支付均走这个方法)
     * @param Trade $trade
     * @return mixed
     * @throws \yii\base\Exception
     */
    public function unifiedOrder(Trade $trade)
    {

    }

    /**
     *
     */
    protected function getHttpClient()
    {
        $this->requestConfig['format'] = Client::FORMAT_XML;
        $this->responseConfig['format'] = Client::FORMAT_XML;
        $this->on(Client::EVENT_BEFORE_SEND, [$this, 'RequestEvent']);
    }

    /**
     * 服务端通知
     * @param Request $request
     * @param string $tradeId
     * @param float $money
     * @param string $message
     * @param string $payId
     * @return mixed
     */
    public function notice(Request $request, &$tradeId, &$money, &$message, &$payId)
    {
        $xml = $request->getRawBody();
        //如果返回成功则验证签名
        try {
            $params = $this->convertXmlToArray($xml);
            $tradeId = $params['out_trade_no'];
            $money = $params['total_fee'];
            $message = $params['return_code'];
            $payId = $params['transaction_id'];
            if ($params['return_code'] == 'SUCCESS' && $params['sign'] == $this->generateSignature($params)) {
                Trade::setPayStatus($tradeId, Trade::STATE_SUCCESS, ['pay_id' => $payId, 'message' => $message]);
                echo '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
                return true;
            }
        } catch (\Exception $e) {
            Yii::error($e->getMessage(), __CLASS__);
        }
        echo '<xml><return_code><![CDATA[FAIL]]></return_code><return_msg><![CDATA[FAIL]]></return_msg></xml>';
        return false;
    }

    /**
     * 支付响应
     * @param Request $request
     * @param $paymentId
     * @param $money
     * @param $message
     * @param $payId
     * @return mixed
     */
    public function callback(Request $request, &$paymentId, &$money, &$message, &$payId)
    {
        return;
    }

    /**
     * 生成签名
     * @param array $params
     * @return string
     * @throws InvalidConfigException
     */
    protected function generateSignature(array $params)
    {
        $bizParameters = [];
        foreach ($params as $k => $v) {
            if ($k != "sign" && $v != "" && !is_array($v)) {
                $bizParameters[$k] = $v;
            }
        }
        ksort($bizParameters);
        $bizString = urldecode(http_build_query($bizParameters) . '&key=' . $this->apiKey);
        if ($this->signType == self::SIGNATURE_METHOD_MD5) {
            $sign = md5($bizString);
        } elseif ($this->signType == self::SIGNATURE_METHOD_SHA256) {
            $sign = hash_hmac('sha256', $bizString, $this->apiKey);
        } else {
            throw new InvalidConfigException ('This encryption is not supported');
        }
        return strtoupper($sign);
    }

    /**
     * 转换XML到数组
     * @param \SimpleXMLElement|string $xml
     * @return array
     */
    protected function convertXmlToArray($xml)
    {
        libxml_disable_entity_loader(true);
        return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }

    /**
     * 初始化私钥
     * @throws InvalidConfigException
     */
    protected function initPrivateKey()
    {
        if (!empty ($this->privateKey)) {
            $privateKey = Yii::getAlias($this->privateKey);
            $this->privateKey = openssl_pkey_get_private("file://" . $privateKey);
            if ($this->privateKey === false) {
                throw new InvalidConfigException(openssl_error_string());
            }
        } else {
            throw new InvalidConfigException ('The "privateKey" property must be set.');
        }
    }

    /**
     * 初始化公钥
     * @throws InvalidConfigException
     */
    protected function initPublicKey()
    {
        if (!empty ($this->publicKey)) {
            $publicKey = Yii::getAlias($this->publicKey);
            $this->publicKey = openssl_pkey_get_public("file://" . $publicKey);
            if ($this->publicKey === false) {
                throw new InvalidConfigException(openssl_error_string());
            }
        } else {
            throw new InvalidConfigException ('The "publicKey" property must be set.');
        }
    }
}