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
 * 图书ISBN验证
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class ISBNValidator extends RegularExpressionValidator
{
    /**
     * @inheritdoc
     */
    public $pattern = '/^978[\d]{10}$|^978-[\d]{10}$/';

    /**
     * @inheritdoc
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        if ($this->message === null) {
            $this->message = Yii::t('yuncms', '{attribute} isn’t a valid isbn number.');
        }
        parent::init();
    }
}