<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\i18n;

use Yii;
use yuncms\helpers\ArrayHelper;

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
        $this->initTranslations();
        $this->initFrameworkTranslation();
    }

    public function initFrameworkTranslation()
    {
        if (!isset($this->translations['yuncms']) && !isset($this->translations['yuncms*'])) {
            $this->translations['yuncms'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en-US',
                'basePath' => '@vendor/yuncms/framework/messages',
            ];
        }
    }

    /**
     * 处理默认翻译清单
     */
    public function initTranslations()
    {
        $manifestFile = Yii::getAlias('@vendor/yuncms/translates.php');
        if (is_file($manifestFile)) {
            $manifest = require($manifestFile);
            $this->translations = ArrayHelper::merge($manifest, $this->translations);
        }
    }
}