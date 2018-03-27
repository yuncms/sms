<?php

namespace yuncms\user\widgets;

use yii\base\Widget;
use yuncms\user\models\LoginForm;

/**
 * Class Login
 * @package yuncms\user\widgets
 */
class Login extends Widget
{
    /** @var bool */
    public $validate = true;

    /** @inheritdoc */
    public function run()
    {
        return $this->render('login', [
            'model' => new LoginForm(),
        ]);
    }
}
