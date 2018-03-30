<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\admin\widgets;

use yuncms\helpers\Html;
use yii\bootstrap\Widget;

/**
 * Class Box
 */
class Box extends Widget
{
    /**
     * @var string the header content
     */
    public $header;

    /**
     * @var string additional header options
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     * @since 2.0.1
     */
    public $headerOptions = [];

    /**
     * @var string the footer content in the modal window.
     */
    public $footer;

    /**
     * @var string additional footer options
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     * @since 2.0.1
     */
    public $footerOptions =[];

    public $closeButton = true;

    public $collapseButton = true;

    public $bodyOptions;

    /**
     * Removes all padding inside widget body
     * @var bool
     */
    public $noPadding;

    public $tools;

    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();

        $this->initOptions();
        $this->_setNoPadding();
        echo Html::beginTag('div', $this->options) . "\n";
        echo $this->renderHeader() . "\n";
        echo $this->renderBodyBegin() . "\n";

    }

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
     * Renders the widget.
     */
    public function run()
    {
        echo $this->renderBodyEnd() . "\n";
        echo "\n" . $this->renderFooter();
        echo "\n" . Html::endTag('div');
        //$this->registerPlugin('modal');
    }

    /**
     * 渲染盒子外框头部
     * @return string the rendering result
     */
    protected function renderHeader()
    {
        $tools = $this->renderTools();
        if ($this->header !== null) {
            $header = Html::tag('h5', "\n" . $this->header . "\n", $this->headerOptions) . "\n" . ($tools !== null ? $tools : '');
            return Html::tag('div', $header, ['class' => 'ibox-title']);
        } else {
            return null;
        }
    }

    /**
     * Renders the opening tag of the ibox body.
     * @return string the rendering result
     */
    protected function renderBodyBegin()
    {
        return Html::beginTag('div', $this->bodyOptions);
    }

    /**
     * Renders the closing tag of the ibox body.
     * @return string the rendering result
     */
    protected function renderBodyEnd()
    {
        return Html::endTag('div');
    }

    /**
     * 填充盒子底部
     * @return string the rendering result
     */
    protected function renderFooter()
    {
        if ($this->footer !== null) {
            Html::addCssClass($this->footerOptions, ['class' => 'ibox-footer']);
            return Html::tag('div', "\n" . $this->footer . "\n", $this->footerOptions);
        } else {
            return null;
        }
    }

    /**
     * Renders the tools
     * @return string the rendering result
     */
    protected function renderTools()
    {
        $tools = '';
        if($this->collapseButton !== false){
            $tools .= '<a class="collapse-link"><i class="fa fa-chevron-up"></i></a>';
        }
        if ($this->closeButton !== false) {
            $tools .= '<a class="close-link"><i class="fa fa-times"></i></a>';
        }
        if (!empty($tools)) {
            return Html::tag('div', $tools, ['class' => 'ibox-tools']);
        } else {
            return null;
        }
    }

    /**
     * Set no padding
     */
    private function _setNoPadding()
    {
        if ($this->noPadding !== null) {
            Html::addCssClass($this->bodyOptions, 'no-padding');
        }
    }

    /**
     * Initializes the widget options.
     * This method sets the default values for various options.
     */
    protected function initOptions()
    {
        $this->options = array_merge([
            'class' => 'ibox float-e-margins',
            'tabindex' => -1,
        ], $this->options);

        Html::addCssClass($this->bodyOptions, 'ibox-content');

    }
}