<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\authclient;

use Yii;

/**
 * Class GitHub
 * @package yuncms\user\clients
 */
class GitHub extends \yii\authclient\clients\GitHub implements ClientInterface
{
    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return Yii::t('yuncms', 'GitHub');
    }

    /** @inheritdoc */
    public function getEmail()
    {
        return isset($this->getUserAttributes()['email']) ? $this->getUserAttributes()['email'] : null;
    }

    /** @inheritdoc */
    public function getUsername()
    {
        return isset($this->getUserAttributes()['login']) ? $this->getUserAttributes()['login'] : null;
    }
}
