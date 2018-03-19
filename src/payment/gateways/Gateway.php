<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\payment\gateways;

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
     * Return timeout.
     *
     * @return int|mixed
     */
    public function getTimeout()
    {
        return $this->timeout ?: self::DEFAULT_TIMEOUT;
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