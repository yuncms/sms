<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notifications\channels;

use yii\base\BaseObject;
use yuncms\notifications\contracts\ChannelInterface;
use yuncms\notifications\Notifiable;

/**
 * Class Channel
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
abstract class Channel  extends BaseObject implements ChannelInterface
{
    const DEFAULT_TIMEOUT = 5.0;

    /**
     * @var float
     */
    protected $timeout;

    /**
     * Return timeout.
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
     * @return $this
     */
    public function setTimeout($timeout)
    {
        $this->timeout = floatval($timeout);
        return $this;
    }
}