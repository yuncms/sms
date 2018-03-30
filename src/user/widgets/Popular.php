<?php

namespace yuncms\user\widgets;

use yii\base\Widget;
use yuncms\user\models\User;

/**
 * Class Popular
 * @package yuncms\user\widgets
 */
class Popular extends Widget
{
    public $limit = 10;

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

    /**
     * @inheritdoc
     */
    public function run()
    {
        $models = User::find()->with('profile')
            ->limit($this->limit)
            ->all();

        return $this->render('popular', [
            'models' => $models,
        ]);
    }
}
