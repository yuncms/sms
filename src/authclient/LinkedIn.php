<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\authclient;

use Yii;

/**
 * Class LinkedIn
 * @package yuncms\user\clients
 */
class LinkedIn extends \yii\authclient\clients\LinkedIn implements ClientInterface
{
    /**
     * @inheritdoc
     */
    protected function defaultTitle() {
        return Yii::t('yuncms','LinkedIn');
    }

    /**
     * @inheritdoc
     */
    public function getEmail()
    {
        return isset($this->getUserAttributes()['email-address']) ? $this->getUserAttributes()['email-address'] : null;
    }

    /**
     * @inheritdoc
     */
    public function getUsername()
    {
        return isset($this->getUserAttributes()['first-name']) ? $this->getUserAttributes()['first-name'] : null;
    }
}
