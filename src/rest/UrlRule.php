<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\rest;


/**
 * Class UrlRule
 * @package yuncms\rest
 * @author Tongle Xu <xutongle@gmail.com>
 */
class UrlRule extends \yii\rest\UrlRule
{
    /**
     * @var array tokens for supporting extra actions in addition to those listed in [[tokens]].
     * The keys are the tokens and the values are the corresponding action IDs.
     * These extra patterns will take precedence over [[tokens]].
     */
    public $extraTokens = [
        //'{year}' => '<year:\\d{4}>',
        //'{language}' => '<language:\\w+>'
    ];

    /**
     * {@inheritdoc}
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->tokens = $this->extraTokens + $this->tokens;
    }
}