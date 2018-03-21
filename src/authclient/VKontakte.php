<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\authclient;

use Yii;

/**
 * Class VKontakte
 * @package yuncms\user\clients
 */
class VKontakte extends \yii\authclient\clients\VKontakte implements ClientInterface
{
    /**
     * @inheritdoc
     */
    public $scope = 'email';


    /**
     * @inheritdoc
     */
    public function getEmail()
    {
        return $this->getAccessToken()->getParam('email');
    }

    /**
     * @inheritdoc
     */
    public function getUsername()
    {
        return isset($this->getUserAttributes()['screen_name']) ? $this->getUserAttributes()['screen_name'] : null;
    }

    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return Yii::t('yuncms', 'VKontakte');
    }
}
