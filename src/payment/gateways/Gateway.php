<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\payment\gateways;

use Yii;
use yii\base\Component;
use yuncms\payment\contracts\GatewayInterface;
use yuncms\payment\traits\GatewayTrait;

/**
 * Class Gateway
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class Gateway extends Component implements GatewayInterface
{
    use GatewayTrait;

    //连接超时
    const DEFAULT_TIMEOUT = 5.0;

    //交易类型
    const TRADE_TYPE_QR_CODE = 'qr-code';//扫码支付
    const TRADE_TYPE_JS_API = 'js-api';//应用内JS API,如微信
    const TRADE_TYPE_APP = 'app';//app支付
    const TRADE_TYPE_WAP = 'wap';//H5支付
    const TRADE_TYPE_WEB = 'web';//PC支付
    const TRADE_TYPE_POS = 'pos';//刷卡支付

    //交易状态
    const STATE_NOT_PAY = 0b0;//未支付
    const STATE_SUCCESS = 0b1;//支付成功
    const STATE_FAILED = 0b10;//支付失败
    const STATE_CLOSED = 0b100;//已关闭
    const STATE_REVOKED = 0b101;//已撤销
    const STATE_ERROR = 0b110;//错误
    const STATE_REFUND = 0b111;//转入退款
    const STATE_REFUND_SUCCESS = 0b11;//转入退款
    const STATE_REFUND_FAILED = 0b11;//转入退款

    /**
     * @var float 连接超时时间
     */
    protected $timeout;

    /**
     * @var array 交易类型和Trade映射
     */
    public $tradeTypeMap = [];

    /**
     * 获取交易类型
     * @param int $tradeType 枚举
     * @param string $defaultValue 默认交易类型
     * @return mixed|string
     */
    public function getTradeType($tradeType, $defaultValue = null)
    {
        return isset($this->tradeTypeMap[$tradeType]) ? $this->tradeTypeMap[$tradeType] : $defaultValue;
    }

    /**
     * Return timeout.
     *
     * @return int|mixed
     */
    public function getTimeout()
    {
        return $this->timeout ?: self::DEFAULT_TIMEOUT;
    }

    /**
     * 生成一个指定长度的随机字符串
     * @param int $length
     * @return string
     * @throws \yii\base\Exception
     */
    protected function generateRandomString($length = 32)
    {
        return Yii::$app->security->generateRandomString($length);
    }

    /**
     * Set timeout.
     *
     * @param int $timeout
     *
     * @return $this
     */
    public function setTimeout($timeout)
    {
        $this->timeout = floatval($timeout);
        return $this;
    }
}