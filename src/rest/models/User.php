<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\rest\models;

use yuncms\helpers\AvatarHelper;

/**
 * Class User
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class User extends \yuncms\user\models\User
{
    /**
     * 客户端允许访问的字段
     * @return array
     */
    public function fields()
    {
        return [
            'id',
            'nickname',
            'faceUrl' => function () {
                return $this->getAvatar(AvatarHelper::AVATAR_MIDDLE);
            },
            "created_datetime" => function () {
                return gmdate(DATE_ISO8601, $this->created_at);
            },
            "updated_datetime" => function () {
                return gmdate(DATE_ISO8601, $this->updated_at);
            },
            'blocked_datetime' => function () {
                return gmdate(DATE_ISO8601, $this->blocked_at);
            }
        ];
    }

    /**
     * 扩展字段定义
     * @return array
     */
    public function extraFields()
    {
        return ['profile', 'extra', 'loginHistories', 'socialAccounts', 'tags'];
    }
}