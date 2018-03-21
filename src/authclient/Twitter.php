<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\authclient;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class Twitter
 * @package yuncms\user\clients
 */
class Twitter extends \yii\authclient\clients\Twitter implements ClientInterface
{
    /**
     * @return string
     */
    public function getUsername()
    {
        return ArrayHelper::getValue($this->getUserAttributes(), 'screen_name');
    }

    /**
     * @return null Twitter does not provide user's email address
     */
    public function getEmail()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return Yii::t('yuncms', 'Twitter');
    }
}
