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
 * Class UploaderFileForm
 */
class UploaderFileForm extends Model
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
                'extensions' => 'apk,rar,zip,tar,gz,tgz,7z,bz2,cab,iso,doc,docx,xls,xlsx,ppt,pptx,pdf,txt,md,xml,xmind',
                'maxSize' => 1024 * 1024 * 20,
                'tooBig' => Yii::t('yuncms', 'File has to be smaller than 20MB'),
            ],
        ]);
    }

    /**
     * 保存图片
     * @return bool
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
                $this->addError('file',$e->getMessage());
            } catch (ErrorException $e) {
                $this->addError('file',$e->getMessage());
            } catch (InvalidConfigException $e) {
                $this->addError('file',$e->getMessage());
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