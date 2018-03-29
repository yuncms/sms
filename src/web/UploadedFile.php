<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\web;

use Yii;
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

    /**
     * Saves the uploaded file to a temp location.
     *
     * @param bool $deleteTempFile whether to delete the temporary file after saving.
     * If true, you will not be able to save the uploaded file again in the current request.
     * @return string|false the path to the temp file, or false if the file wasn't saved successfully
     * @see error
     * @throws \yii\base\Exception
     */
    public function saveAsTempFile(bool $deleteTempFile = true)
    {
        if ($this->error != UPLOAD_ERR_OK) {
            return false;
        }
        $tempFilename = uniqid(pathinfo($this->name, PATHINFO_FILENAME), true) . '.' . pathinfo($this->name, PATHINFO_EXTENSION);
        $tempPath = Yii::$app->getPath()->getTempPath() . DIRECTORY_SEPARATOR . $tempFilename;
        if (!$this->saveAs($tempPath, $deleteTempFile)) {
            return false;
        }
        return $tempPath;
    }
}