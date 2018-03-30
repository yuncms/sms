<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\rest\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use yuncms\attachment\components\Uploader;

/**
 * Class UploaderAudioForm
 */
class UploaderAudioForm extends Model
{

    /**
     * @var UploadedFile
     */
    public $file;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        return ArrayHelper::merge($rules, [
            [['file'],
                'file',
                'skipOnEmpty' => false,
                'checkExtensionByMimeType' => false,
                'extensions' => 'mp3,wma,flac,ape,aac,ogg,m4a,amr',
                'maxSize' => 1024 * 1024 * 20,
                'maxFiles' => 1,
                'tooBig' => Yii::t('yuncms', 'File has to be smaller than 20MB'),
            ],
        ]);
    }

    /**
     * 保存图片
     * @return boolean
     */
    public function save()
    {
        if ($this->validate() && $this->file instanceof UploadedFile) {
            $uploader = new Uploader();
            $uploader->up($this->file);
            $fileInfo = $uploader->getFileInfo();
            $this->file = $fileInfo['url'];
            return true;
        } else {
            return false;
        }
    }

    public function beforeValidate()
    {
        $this->file = UploadedFile::getInstanceByName('file');
        return parent::beforeValidate();
    }
}