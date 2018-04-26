<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notifications\rest\models;

use yuncms\rest\models\User;

/**
 * Class Notification
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class DatabaseNotification extends \yuncms\notifications\models\DatabaseNotification
{
    /**
     * 返回可访问的字段
     * @return array
     */
    public function fields()
    {
        $fields = [
            'id',
            'verb',
            'template',
            'data',
            'content',
            'read_at',
            'created_at',
        ];
        return $fields;
    }
}