<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\models;

use Yii;
use yii\imagine\Image;
use yii\web\UploadedFile;
use yuncms\base\Model;
use yuncms\helpers\FileHelper;
use yuncms\helpers\PathHelper;

/**
 * Class PortraitForm
 * @package yuncms\user
 */
class AvatarForm extends Model
{
    /**
     * @var \yii\web\UploadedFile 头像上传字段
     */
    public $file;

    public $x;

    public $y;

    /**
     * @var int 宽度
     */
    public $width;

    /**
     * @var int 高度
     */
    public $height;

    /** @var User */
    private $_user;

    /**
     * @var array 头像尺寸
     */
    private $_avatarSize = [
        'big' => 200,
        'middle' => 128,
        'small' => 48
    ];

    /**
     * @var string
     */
    private $_originalImage;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['x', 'y', 'width', 'height'], 'integer'],
            [['file'], 'required'],
            [['file'], 'file', 'extensions' => 'gif, jpg, png', 'maxSize' => 1024 * 1024 * 2,
                'tooBig' => Yii::t('yuncms', 'File has to be smaller than 2MB')],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'portrait' => Yii::t('yuncms', 'Portrait'),
        ];
    }

    /**
     * 保存头像
     *
     * @return boolean
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\base\Exception
     * @throws \yii\base\ErrorException
     */
    public function save()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            $volume = Yii::$app->getFilesystem()->get(Yii::$app->settings->get('avatarVolume', 'user'));
            $avatarPath = PathHelper::getAvatarPath($user->id);
            foreach ($this->_avatarSize as $size => $value) {
                $tempFile = Yii::$app->getPath()->getTempPath() . DIRECTORY_SEPARATOR . $user->id . '_avatar_' . $size . '.jpg';
                Image::thumbnail($this->getOriginalImage(), $value, $value)->save($tempFile, ['quality' => 100]);
                $currentAvatarPath = $avatarPath . "_avatar_{$size}.jpg";
                $volume->write($currentAvatarPath, FileHelper::readAndDelete($tempFile));
            }
            $user->avatar = true;
            $user->save();
            return true;
        }
        return false;
    }

    /*
     * @return User
     */
    public function getUser()
    {
        if ($this->_user == null) {
            $this->_user = Yii::$app->user->identity;
        }
        return $this->_user;
    }

    /**
     * 获取原图路径
     * @return string
     * @throws \yii\base\Exception
     */
    public function getOriginalImage()
    {
        if ($this->_originalImage == null) {
            $this->_originalImage = Yii::$app->getPath()->getTempPath() . DIRECTORY_SEPARATOR . $this->getUser()->id . '_avatar.jpg';
        }
        return $this->_originalImage;
    }

    /**
     * 验证前 处理上传
     * @return bool
     */
    public function beforeValidate()
    {
        $this->file = UploadedFile::getInstance($this, 'file');
        return parent::beforeValidate();
    }

    /**
     * 验证后保存原图
     * @throws \yii\base\Exception
     */
    public function afterValidate()
    {
        //保存原图
        Image::crop($this->file->tempName, $this->width, $this->height, [$this->x, $this->y])->save($this->getOriginalImage(), ['quality' => 100]);
        parent::afterValidate();
    }
}