<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\widgets;

use yii\base\BaseObject;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\base\InvalidConfigException;

/**
 * Bloodhound is a helper class to configure Bloodhound suggestion engines.
 * @package yuncms\widgets
 */
class BootstrapTypeAheadBloodhound extends BaseObject
{
    /**
     * @var string the engine js name
     */
    public $name;

    /**
     * @var array the configuration of Bloodhound suggestion engine.
     * @see https://github.com/twitter/typeahead.js/blob/master/doc/bloodhound.md#options
     */
    public $clientOptions = [];

    /**
     * @inheritdoc
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        if ($this->name === null) {
            throw new InvalidConfigException("'name' cannot be null.");
        }
        parent::init();
    }

    /**
     * Returns the engine adapter. To be used to configure [[TypeAhead::dataSets]] `source` option.
     * @return JsExpression
     */
    public function getAdapterScript()
    {
        return new JsExpression("{$this->name}.ttAdapter()");
    }

    /**
     * Returns the javascript initialization code
     * @return string
     */
    public function getClientScript()
    {
        $options = $this->clientOptions !== false && !empty($this->clientOptions) ? Json::encode($this->clientOptions) : '{}';
        return "var {$this->name} = new Bloodhound($options);{$this->name}.initialize();";
    }
}