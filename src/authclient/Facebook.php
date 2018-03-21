<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\authclient;

use Yii;

/**
 * Class Facebook
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class Facebook extends \yii\authclient\clients\Facebook implements ClientInterface
{
    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return Yii::t('yuncms', 'Facebook');
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
}