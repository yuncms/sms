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
 * Class WeiBo
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class WeiBo extends OAuth2 implements ClientInterface
{
    /**
     * @inheritdoc
     */
    public $authUrl = 'https://api.weibo.com/oauth2/authorize';
    /**
     * @inheritdoc
     */
    public $tokenUrl = 'https://api.weibo.com/oauth2/access_token';
    /**
     * @inheritdoc
     */
    public $apiBaseUrl = 'https://api.weibo.com';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->scope === null) {
            $this->scope = implode(',', [
                'follow_app_official_microblog',
            ]);
        }
    }

    /**
     * @inheritdoc
     */
    protected function initUserAttributes()
    {
        return $this->api("2/users/show.json", 'GET', ['uid' => $this->getOpenId()]);
    }

    /**
     * 返回OpenId
     * @return mixed
     */
    public function getOpenId()
    {
        $tokenInfo = $this->api('oauth2/get_token_info', 'POST');
        return $tokenInfo['uid'];
    }

    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'weibo';
    }

    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return Yii::t('yuncms', 'Weibo');
    }

    /**
     * @inheritdoc
     */
    public function getEmail()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getUsername()
    {
        return isset($this->getUserAttributes()['name']) ? $this->getUserAttributes()['name'] : null;
    }

    /**
     * @inheritdoc
     */
    protected function defaultViewOptions()
    {
        return [
            'popupWidth' => 800,
            'popupHeight' => 500,
        ];
    }
}