<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

if (PHP_MAJOR_VERSION>=7 && PHP_MINOR_VERSION>=1) {
    // skip deprecation errors in PHP 7.1 and above
    error_reporting(E_ALL & ~E_DEPRECATED);
}
defined('YII_ENABLE_EXCEPTION_HANDLER') or define('YII_ENABLE_EXCEPTION_HANDLER',false);
defined('YII_ENABLE_ERROR_HANDLER') or define('YII_ENABLE_ERROR_HANDLER',false);
defined('YII_DEBUG') or define('YII_DEBUG',true);

$_SERVER['SCRIPT_NAME'] = '/' . __DIR__;
$_SERVER['SCRIPT_FILENAME'] = __FILE__;

require __DIR__.'/../vendor/autoload.php';
require_once(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');
