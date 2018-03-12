<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notifications\messages;

/**
 * 移动终端消息
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class AppMessage extends BaseMessage
{
    /**
     * @var string 推送目标
     */
    public $target = 'ALL';

    /**
     * @var string 目标
     */
    public $targetValue = 'all';

    /**
     * @var array 扩展参数
     */
    public $extParameters;


}