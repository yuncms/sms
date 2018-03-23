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

    const DEFAULT_TIMEOUT = 5.0;

    /**
     * @var float
     */
    protected $timeout;

    /**
     * @var array 交易类型和Trade映射
     */
    public $tradeTypeMap = [

    ];

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