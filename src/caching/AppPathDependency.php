<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\caching;

use Yii;
use yii\caching\Cache;
use yii\caching\Dependency;

class AppPathDependency extends Dependency
{
    /**
     * @var string Craft’s base path
     */
    public $appPath;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->reusable = true;
        parent::init();
    }

    /**
     * Generates the data needed to determine if dependency has been changed.
     *
     * @param Cache $cache The cache component that is currently evaluating this dependency.
     *
     * @return string The data needed to determine if dependency has been changed.
     */
    protected function generateDependencyData($cache): string
    {
        return $this->appPath = Yii::$app->getBasePath();
    }
}