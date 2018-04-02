<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\actions;

use Yii;
use yii\base\Action;
use yii\base\Exception;
use yii\validators\FileValidator;
use yii\web\BadRequestHttpException;
use yuncms\helpers\Html;
use yuncms\helpers\UploadHelper;
use yuncms\web\Response;
use yuncms\web\UploadedFile;

/**
 * Class UploadAction
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class UploadAction extends Action
{
    /**
     * @var string file input name.
     */
    public $uploadParam = 'file';

    /**
     * @var string Validator name
     */
    public $onlyImage = true;

    /**
     * @var bool 是否允许批量上传
     */
    public $multiple = false;

    /**
     * @var string 参数指定文件名
     */
    public $uploadQueryParam = 'file_param';

    private $_config = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->controller->enableCsrfValidation = false;
        if (Yii::$app->request->get($this->uploadQueryParam)) {
            $this->uploadParam = Yii::$app->request->get($this->uploadQueryParam);
        }

        $this->_config['maxSize'] = UploadHelper::getMaxUploadSize();
        if ($this->multiple) {
            $this->_config['maxFiles'] = (int)(ini_get('max_file_uploads'));
        }
        if ($this->onlyImage !== true) {
            $this->_config['extensions'] = Yii::$app->getSettings()->get('fileAllowFiles', 'attachment');
        } else {
            $this->_config['extensions'] = Yii::$app->getSettings()->get('imageAllowFiles', 'attachment');
            $this->_config['checkExtensionByMimeType'] = true;
            $this->_config['mimeTypes'] = 'image/*';
        }
    }

    /**
     * @inheritdoc
     * @throws BadRequestHttpException
     * @throws \yii\base\ErrorException
     */
    public function run()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->request->isPost) {
            /** @var UploadedFile[] $files */
            $files = UploadedFile::getInstancesByName($this->uploadParam);
            if (!$this->multiple) {
                $res = [$this->uploadOne($files[0])];
            } else {
                $res = $this->uploadMore($files);
            }
            return ['files' => $res];
        } else {
            throw new BadRequestHttpException('Only POST is allowed');
        }
    }

    /**
     * 批量上传
     * @param array $files
     * @return array
     * @throws \yii\base\ErrorException
     */
    private function uploadMore(array $files)
    {
        $res = [];
        foreach ($files as $file) {
            $result = $this->uploadOne($file);
            $res[] = $result;
        }
        return $res;
    }

    /**
     * 单文件上传
     * @param UploadedFile $file
     * @return array|mixed
     * @throws \yii\base\ErrorException
     */
    private function uploadOne(UploadedFile $file)
    {
        try {
            $validator = new FileValidator([$this->_config]);
            if ($validator->validate($file, $error)) {
                $fileInfo = $file->save();
                $result = [
                    'name' => Html::encode($fileInfo->filename),
                    'url' => $fileInfo->getUrl(),
                    'path' => $fileInfo->path,
                    'extension' => $file->extension,
                    'type' => $fileInfo->type,
                    'size' => $fileInfo->size
                ];
                if ($this->onlyImage !== true) {
                    $result['filename'] = $result['name'];
                }
            } else {
                $result = [
                    'error' => $error
                ];
            }
        } catch (Exception $e) {
            $result = [
                'error' => $e->getMessage()
            ];
        }
        return $result;
    }
}