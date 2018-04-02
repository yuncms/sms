<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\actions;


use Yii;
use yii\base\Action;
use yuncms\helpers\Html;
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

        $this->_config['maxSize'] = $this->getModule()->getMaxUploadByte();
        if ($this->multiple) {
            $this->_config['maxFiles'] = (int)(ini_get('max_file_uploads'));
        }
        if ($this->onlyImage !== true) {
            $this->_config['extensions'] = $this->getModule()->fileAllowFiles;
        } else {
            $this->_config['extensions'] = $this->getModule()->imageAllowFiles;
            $this->_config['checkExtensionByMimeType'] = true;
            $this->_config['mimeTypes'] = 'image/*';
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->request->isPost) {
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
     */
    private function uploadOne(UploadedFile $file)
    {
        try {
            $uploader = new Uploader(['config' => $this->_config]);
            $uploader->up($file);
            $fileInfo = $uploader->getFileInfo();
            $result = [
                'name' => Html::encode($file->name),
                'url' => $fileInfo['url'],
                'path' => $fileInfo['url'],
                'extension' => $file->extension,
                'type' => $file->type,
                'size' => $file->size
            ];
            if ($this->onlyImage !== true) {
                $result['filename'] = $result['name'];
            }

        } catch (Exception $e) {
            $result = [
                'error' => $e->getMessage()
            ];
        }
        return $result;
    }
}