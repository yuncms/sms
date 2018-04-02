<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\payment\channels;

use Yii;
use yii\base\InvalidConfigException;
use yuncms\base\HasHttpRequest;
use yuncms\helpers\ArrayHelper;
use yuncms\helpers\Json;
use yuncms\payment\Channel;

/**
 * Class Alipay
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class Alipay extends Channel
{
    use HasHttpRequest;

    const SIGNATURE_METHOD_RSA = 'RSA';
    const SIGNATURE_METHOD_RSA2 = 'RSA2';

    /**
     * @var integer
     */
    public $appId;

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
    public $signType = self::SIGNATURE_METHOD_RSA2;

    /**
     * @var string 网关地址
     */
    public $baseUrl = 'https://openapi.alipay.com/gateway.do';

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
     * 初始化私钥
     * @throws InvalidConfigException
     */
    protected function initPrivateKey()
    {
        $privateKey = Yii::getAlias($this->privateKey);
        if (!is_file($privateKey)) {
            $privateKey = "-----BEGIN RSA PRIVATE KEY-----\n" .
                wordwrap($this->privateKey, 64, "\n", true) .
                "\n-----END RSA PRIVATE KEY-----";
        } else {
            $privateKey = "file://" . $privateKey;
        }
        $this->privateKey = openssl_pkey_get_private($privateKey);
        if ($this->privateKey === false) {
            throw new InvalidConfigException(openssl_error_string());
        }
    }

    /**
     * 初始化公钥
     * @throws InvalidConfigException
     */
    protected function initPublicKey()
    {
        $publicKey = Yii::getAlias($this->publicKey);
        if (!is_file($publicKey)) {
            $publicKey = "-----BEGIN PUBLIC KEY-----\n" .
                wordwrap($this->publicKey, 64, "\n", true) .
                "\n-----END PUBLIC KEY-----";
        } else {
            $publicKey = "file://" . $publicKey;
        }
        $this->publicKey = openssl_pkey_get_public($publicKey);
        if ($this->publicKey === false) {
            throw new InvalidConfigException(openssl_error_string());
        }
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return Yii::t('yuncms', 'Alipay');
    }

    /**
     * 生成签名
     * @param array $params
     * @return string
     * @throws InvalidConfigException
     */
    public function generateSignature(array $params)
    {
        //签名
        $sign = '';
        if ($this->signType == self::SIGNATURE_METHOD_RSA2) {
            $sign = openssl_sign($this->getSignatureContent($params), $sign, $this->privateKey, OPENSSL_ALGO_SHA256) ? base64_encode($sign) : null;
        } elseif ($this->signType == self::SIGNATURE_METHOD_RSA) {
            $sign = openssl_sign($this->getSignatureContent($params), $sign, $this->privateKey, OPENSSL_ALGO_SHA1) ? base64_encode($sign) : null;
        } else {
            throw new InvalidConfigException ('This encryption is not supported');
        }
        return $sign;
    }

    /**
     * 发情请求
     * @param array $params
     * @return array
     * @throws InvalidConfigException
     */
    public function sendRequest($params = [])
    {
        $defaultParams = [
            'app_id' => $this->appId,
            'format' => 'JSON',
            'charset' => 'utf-8',
            'sign_type' => $this->signType,
            'timestamp' => date('Y-m-d H:i:s'),
            'version' => '1.0',
        ];
        $params = ArrayHelper::merge($defaultParams, $params);
        $params['biz_content'] = Json::encode($params['biz_content']);
        $params['sign'] = $this->generateSignature($params);
        $result = $this->post($this->baseUrl, $params);
        return $result;
    }

    /**
     * 数据签名处理
     * @param array $toBeSigned
     * @param bool $verify
     * @return bool|string
     */
    protected function getSignatureContent(array $toBeSigned, $verify = false)
    {
        ksort($toBeSigned);
        $stringToBeSigned = '';
        foreach ($toBeSigned as $k => $v) {
            if ($verify && $k != 'sign' && $k != 'sign_type') {
                $stringToBeSigned .= $k . '=' . $v . '&';
            }
            if (!$verify && $v !== '' && !is_null($v) && $k != 'sign' && '@' != substr($v, 0, 1)) {
                $stringToBeSigned .= $k . '=' . $v . '&';
            }
        }
        $stringToBeSigned = substr($stringToBeSigned, 0, -1);
        unset($k, $v);
        return $stringToBeSigned;
    }
}