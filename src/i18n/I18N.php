<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\i18n;

/**
 * Class I18N
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class I18N extends \yii\i18n\I18N
{
    /**
     * Initializes the component by configuring the default message categories.
     */
    public function init()
    {
        parent::init();
        if (!isset($this->translations['yuncms']) && !isset($this->translations['yuncms*'])) {
            $this->translations['yuncms'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en-US',
                'basePath' => '@vendor/yuncms/framework/messages',
            ];
        }
    }
}