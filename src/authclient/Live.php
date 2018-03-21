<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\authclient;

use Yii;

/**
 * Class Live
 * @package yuncms\user\clients
 */
class Live extends \yii\authclient\clients\Live implements ClientInterface
{
    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return Yii::t('yuncms', 'Live');
    }

    /**
     * @inheritdoc
     */
    public function getEmail()
    {
        return isset($this->getUserAttributes()['account']) ? $this->getUserAttributes()['account'] : null;
    }

    /**
     * @inheritdoc
     */
    public function getUsername()
    {
        return isset($this->getUserAttributes()['name']) ? $this->getUserAttributes()['name'] : null;
    }
}