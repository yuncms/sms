<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\web;

use yuncms\helpers\FileHelper;

/**
 * Class UploadedFile
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class UploadedFile extends \yii\web\UploadedFile
{
    /**
     * @return string file extension
     * @throws \yii\base\InvalidConfigException
     */
    public function getMimeType()
    {
        return FileHelper::getMimeType($this->tempName);
    }
}