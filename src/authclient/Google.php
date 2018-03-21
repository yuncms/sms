<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\authclient;

use Yii;

/**
 * Class Google
 * @package yuncms\user\clients
 */
class Google extends \yii\authclient\clients\Google implements ClientInterface
{
    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return Yii::t('yuncms', 'Google');
    }

    /**
     * @inheritdoc
     */
    public function getEmail()
    {
        return isset($this->getUserAttributes()['emails'][0]['value']) ? $this->getUserAttributes()['emails'][0]['value'] : null;
    }

    /**
     * @inheritdoc
     */
    public function getUsername()
    {
        return null;
    }
}
