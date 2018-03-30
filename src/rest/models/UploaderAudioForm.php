<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\rest\models;

use League\Flysystem\FileExistsException;
use Yii;
use yii\base\ErrorException;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yuncms\helpers\ArrayHelper;
use yuncms\web\UploadedFile;

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
     * @throws \yii\base\Exception
     */
    public function save()
    {
        if ($this->validate() && $this->file instanceof UploadedFile) {
            try {
                if (($uploader = $this->file->save()) != false) {
                    $this->file = $uploader->getUrl();
                    return true;
                } else {
                    $this->addError('file', 'File storage failed.');
                }
            } catch (FileExistsException $e) {
                $this->addError('file', $e->getMessage());
            } catch (ErrorException $e) {
                $this->addError('file', $e->getMessage());
            } catch (InvalidConfigException $e) {
                $this->addError('file', $e->getMessage());
            }
        }
        return false;
    }

    public function beforeValidate()
    {
        $this->file = UploadedFile::getInstanceByName('file');
        return parent::beforeValidate();
    }
}