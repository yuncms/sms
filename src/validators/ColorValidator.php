<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\validators;

use Yii;
use yii\validators\RegularExpressionValidator;

/**
 * Class ColorValidator
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class ColorValidator extends RegularExpressionValidator
{

    /**
     * @inheritdoc
     */
    public $pattern = '/^#[0-9a-f]{6}$/';

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->message === null) {
            $this->message = Yii::t('yuncms', '{attribute} isn’t a valid hex color value.');
        }
        parent::init();
    }

}