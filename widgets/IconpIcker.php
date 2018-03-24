<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\widgets;

use yii\widgets\InputWidget;
use yii\helpers\Html;

/**
 * Class IconpIcker
 */
class IconpIcker extends InputWidget
{
    public $buttonOptions = [];

    /**
     * {@inheritDoc}
     * @see \yii\base\Object::init()
     */
    public function init()
    {
        parent::init();
        if (!isset($this->options['class'])) {
            $this->options['class'] = 'form-control';
        }
        $this->buttonOptions = array_merge([
            'id' => "{$this->options['id']}_btn",
            'class' => 'btn btn-default',
            'role' => 'iconpicker',
            'data' => [
                'rows' => 10,
                'cols' => 15,
                'iconset' => 'fontawesome',
                'placement' => 'left',
            ]

        ], $this->buttonOptions);
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        echo Html::beginTag('div', ['class' => 'input-group col-sm-4']);
        if ($this->hasModel()) {
            $this->buttonOptions['data']['icon'] = $this->model->{$this->attribute};
            echo Html::activeTextInput($this->model, $this->attribute, $this->options);
        } else {
            $this->buttonOptions['data']['icon'] = $this->value;
            echo Html::textInput($this->name, $this->value, $this->options);
        }
        echo Html::beginTag('div', ['class' => 'input-group-btn']);
        echo Html::button('', $this->buttonOptions);
        echo Html::endTag('div');
        echo Html::endTag('div');
        $view = $this->getView();
        IconpIckerAsset::register($view);

        $view->registerJs("$('#{$this->options['id']}_btn').on('change', function(e) { $('#{$this->options['id']}').val(e.icon);});");
    }
}