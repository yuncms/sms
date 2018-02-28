<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notifications;

use yii\base\Component;

/**
 * 通知管理
 *
 * ```php
 * [
 *     'components' => [
 *         'notification' => [
 *             'channels' => [
 *                 'db' => [
 *                     'class' => 'yuncms\notifications\channels\DBChannel',
 *                 ],
 *                 'email' => [
 *                     'class' => 'yuncms\notifications\channels\EmailChannel',
 *                     'message' => [
 *                         'from' => 'admin@example.com',
 *                     ],
 *                 ],
 *             ],
 *         ],
 *     ],
 * ]
 * ```
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class NotificationManager extends Component
{
    /**
     * @var Channel[] 渠道配置
     */
    public $channels = [];
}