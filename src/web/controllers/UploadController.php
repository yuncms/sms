<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\web\controllers;

use yii\filters\AccessControl;
use yuncms\web\Controller;

/**
 * Class UploadController
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class UploadController extends Controller
{
    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'file-upload', 'files-upload', 'image-upload', 'images-upload',
                        ],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     * @return array
     */
    public function actions()
    {
        return [
            'file-upload' => [
                'class' => 'yuncms\actions\UploadAction',
                'multiple' => false,
                'onlyImage' => false,
            ],
            'files-upload' => [
                'class' => 'yuncms\actions\UploadAction',
                'multiple' => true,
                'onlyImage' => false,
            ],
            'image-upload' => [
                'class' => 'yuncms\actions\UploadAction',
                'multiple' => false,
                'onlyImage' => true,
            ],
            'images-upload' => [
                'class' => 'yuncms\actions\UploadAction',
                'multiple' => true,
                'onlyImage' => true,
            ],
        ];
    }
}