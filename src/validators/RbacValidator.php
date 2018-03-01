<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */


namespace yuncms\validators;

use Yii;
use yii\validators\Validator;

class RbacValidator extends Validator
{
    /** @var \yii\rbac\DbManager */
    protected $manager;

    /** @inheritdoc */
    public function init()
    {
        parent::init();
        $this->manager = Yii::$app->authManager;
    }

    /** @inheritdoc */
    protected function validateValue($value)
    {
        if (!is_array($value)) {
            return [Yii::t('yuncms', 'Invalid value'), []];
        }

        foreach ($value as $val) {
            if ($this->manager->getItem($val) == null) {
                return [Yii::t('yuncms', 'There is neither role nor permission with name "{0}"', [$val]), []];
            }
        }
    }
}