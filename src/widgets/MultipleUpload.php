<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\widgets;

use yii\helpers\Url;
use yii\base\Arrayable;
use yii\jui\JuiAsset;
use yii\web\JsExpression;
use yii\widgets\InputWidget;
use yuncms\helpers\ArrayHelper;
use yuncms\helpers\Html;
use yuncms\helpers\Json;

/**
 * Class MultipleUpload
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class MultipleUpload extends InputWidget
{
    /**
     * @var bool 是否只允许上传图片
     */
    public $onlyImage = true;

    /**
     * @var array
     */
    public $wrapperOptions;

    /**
     * @var array 客户端参数
     */
    public $clientOptions = [];

    /**
     * @var array 上传url地址
     */
    public $url = [];

    /**
     * 这里为了配合后台方便处理所有都是设为true,文件上传数目请控制好 $maxNumberOfFiles
     * @var bool 是否允许多文件上传
     */
    public $multiple = true;

    /**
     *
     * @var bool
     */
    public $sortable = false;

    /**
     *
     * @var int 允许上传的最大文件数目
     */
    public $maxNumberOfFiles;

    /**
     * @var int 允许上传文件最大限制
     */
    public $maxFileSize;

    /**
     * @var string 允许上传的附件类型
     */
    public $acceptFileTypes;

    private $fileInputName;

    /**
     *
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
        if (!isset ($this->options ['id'])) {
            $this->options ['id'] = $this->getId();
        }
        if (empty($this->maxFileSize)) {
            $this->maxFileSize = ini_get('upload_max_filesize') ?: '2M';
        }
        if (empty($this->maxNumberOfFiles)) {
            $this->maxNumberOfFiles = (int)ini_get('max_file_uploads') ?: 50;
        }
        if (empty($this->url)) {
            if ($this->onlyImage === false) {
                $this->url = $this->multiple ? ['/attachment/upload/files-upload'] : ['/attachment/upload/file-upload'];
                $fileAllowFiles = $this->getModule()->fileAllowFiles;
                $regExp = '/(\.|\/)(';
                $extensions = explode(',', $fileAllowFiles);
                foreach ($extensions as $extension) {
                    $regExp .= $extension . '|';
                }
                $regExp .= 'xyz)$/i';

                // $this->acceptFileTypes = 'application/*, text/*';
                $this->clientOptions['acceptFileTypes'] = new JsExpression($regExp);
            } else {
                $this->url = $this->multiple ? ['/attachment/upload/images-upload'] : ['/attachment/upload/image-upload'];
                //$this->acceptFileTypes = 'image/*';
                $this->acceptFileTypes = 'image/png, image/jpg, image/jpeg, image/gif';
                $this->clientOptions['acceptFileTypes'] = new JsExpression('/(\.|\/)(gif|jpe?g|png)$/i');
            }
        }
        if ($this->hasModel()) {
            $value = Html::getAttributeValue($this->model, $this->attribute);
            $this->name = $this->name ?: Html::getInputName($this->model, $this->attribute);
            $this->attribute = Html::getAttributeName($this->attribute);
            //$value = $this->model->{$this->attribute};
            $attachments = $this->multiple == true ? $value : [$value];
            $this->value = [];
            if ($attachments) {
                foreach ($attachments as $attachment) {
                    $value = $this->formatAttachment($attachment);
                    if ($value) {
                        $this->value[] = $value;
                    }
                }
            }
        }
        $this->fileInputName = md5($this->name);
        if (!array_key_exists('file_param', $this->url)) {
            $this->url['file_param'] = $this->fileInputName;//服务器需要通过这个判断是哪一个input name上传的
        }

        $this->clientOptions = ArrayHelper::merge($this->clientOptions, [
            'id' => $this->options ['id'],
            'name' => $this->name, //主要用于上传后返回的项目name
            'url' => Url::to($this->url),
            'multiple' => $this->multiple,
            'sortable' => $this->sortable,
            'maxNumberOfFiles' => $this->maxNumberOfFiles,
            'maxFileSize' => $this->maxFileSize,
//            'acceptFileTypes' => function () {
//                if ($this->onlyImage === false) {
//                    return $this->acceptFileTypes;
//                } else {
//                    return new \yii\web\JsExpression('/(\.|\/)(gif|jpe?g|png)$/i');
//                }
//            },
            'files' => $this->value ?: []
        ]);
    }

    /**
     * 格式化附件
     * @param $attachment
     * @return array
     */
    protected function formatAttachment($attachment)
    {
        if (!empty($attachment) && is_string($attachment)) {
            return ['url' => $attachment, 'path' => $attachment,];
        } else if (is_array($attachment)) {
            return $attachment;
        } else if ($attachment instanceof Arrayable)
            return $attachment->toArray();
        return [];
    }

    /**
     *
     * @return string
     */
    public function run()
    {
        $this->registerClientScript();
        $content = Html::hiddenInput($this->name . ($this->multiple ? '[]' : ''), null, $this->options);
        $content .= Html::beginTag('div', $this->wrapperOptions);
        $content .= Html::fileInput($this->fileInputName, null, [
            'id' => $this->fileInputName,
            'multiple' => $this->multiple,
            'accept' => $this->acceptFileTypes
        ]);
        $content .= Html::endTag('div');
        return $content;
    }

    /**
     * Registers required script for the plugin to work as jQuery File Uploader
     */
    public function registerClientScript()
    {
        Html::addCssClass($this->wrapperOptions, "upload-kit");
        AttachmentUploadAsset::register($this->getView());
        if ($this->sortable) {
            JuiAsset::register($this->getView());
        }
        $options = Json::encode($this->clientOptions);
        $this->getView()->registerJs("jQuery('#{$this->fileInputName}').attachmentUpload({$options});");
    }
}