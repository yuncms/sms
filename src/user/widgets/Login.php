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

    /**
     * Creates a widget instance and runs it.
     * The widget rendering result is returned by this method.
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @return string the rendering result of the widget.
     */
    public static function widget($config = [])
    {
        try {
            return parent::widget($config);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /** @inheritdoc */
    public function run()
    {
        return $this->render('login', [
            'model' => new LoginForm(),
        ]);
    }
}
