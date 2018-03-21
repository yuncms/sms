<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\authclient;

use Yii;
use yii\authclient\OAuth2;

/**
 * Class Douban
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class Douban extends OAuth2 implements ClientInterface
{
    /**
     * @inheritdoc
     */
    public $authUrl = 'https://www.douban.com/service/auth2/auth';
    /**
     * @inheritdoc
     */
    public $tokenUrl = 'https://www.douban.com/service/auth2/token';
    /**
     * @inheritdoc
     */
    public $apiBaseUrl = 'https://api.douban.com/';
    /**
     * @inheritdoc
     */
    public $scope = 'douban_basic_common';

    /**
     * @inheritdoc
     */
    protected function initUserAttributes()
    {
        return $this->api('v2/user/~me', 'GET');
    }

    /**
     * @return array
     * @see http://developers.douban.com/wiki/?title=user_v2#User
     */
    public function getUserInfo()
    {
        return $this->getUserAttributes();
    }

    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'douban';
    }

    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return Yii::t('yuncms', 'Douban');
    }

    /**
     * @inheritdoc
     */
    public function getEmail()
    {
        return isset($this->getUserAttributes()['email']) ? $this->getUserAttributes()['email'] : null;
    }

    /**
     * @inheritdoc
     */
    public function getUsername()
    {
        return null;
    }

    protected function defaultViewOptions()
    {
        return ['popupWidth' => 1000, 'popupHeight' => 500];
    }
}