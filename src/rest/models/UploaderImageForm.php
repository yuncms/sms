<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\rest\models;

use Yii;
use yii\base\ErrorException;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yuncms\web\UploadedFile;
use yuncms\helpers\ArrayHelper;
use League\Flysystem\FileExistsException;

/**
 * 图像上传模型
 */
class UploaderImageForm extends Model
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
                'extensions' => 'gif,jpg,jpeg,png',
                'maxSize' => 1024 * 1024 * 2,
                'tooBig' => Yii::t('yuncms', 'File has to be smaller than 2MB'),
            ],
        ]);
    }

    /**
     * 保存图片
     * @return boolean
     * @throws \Exception
     */
    public function save()
    {
        if ($this->validate() && $this->file instanceof UploadedFile) {
            try {
                if (($uploader = $this->file->save()) != false) {
                    $this->file = $uploader->getUrl();
                    return true;
                } else {
                    $this->addError('file', Yii::t('yuncms', 'Image storage failed.'));
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