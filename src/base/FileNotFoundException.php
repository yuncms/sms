<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\base;

use yii\base\Exception;

/**
 * Class FileNotFoundException
 * @package yuncms\filesystem\exceptions
 */
class FileNotFoundException extends Exception
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'File Not Found';
    }
}