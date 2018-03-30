<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\rest\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;
use yuncms\rest\Controller;
use yuncms\rest\models\AvatarForm;
use yuncms\rest\models\UserSettingsForm;
use yuncms\rest\models\UserRecoveryForm;
use yuncms\rest\models\UserRegistrationForm;
use yuncms\rest\models\UserEmailRegistrationForm;
use yuncms\rest\models\UserMobileRegistrationForm;

/**
 * 用户接口
 * @package api\modules\v1\controllers
 */
class UserController extends Controller
{
    /**
     * Declares the allowed HTTP verbs.
     * Please refer to [[VerbFilter::actions]] on how to declare the allowed verbs.
     * @return array the allowed HTTP verbs.
     */
    protected function verbs()
    {
        return [
            'register' => ['POST'],
            'mobile-register' => ['POST'],
            'email-register' => ['POST'],
            'password' => ['POST'],
            'recovery' => ['POST'],
        ];
    }

    /**
     * @return UserRegistrationForm|false|\yuncms\user\models\User
     * @throws ServerErrorHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionRegister()
    {
        $model = new UserRegistrationForm();
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if (($user = $model->register()) != false) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            $id = implode(',', array_values($user->getPrimaryKey(true)));
            $response->getHeaders()->set('Location', Url::toRoute(['view', 'id' => $id], true));
            return $user;
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
        return $model;
    }

    /**
     * 注册用户
     * @return UserMobileRegistrationForm|false|\yuncms\user\models\User
     * @throws ServerErrorHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionMobileRegister()
    {
        $model = new UserMobileRegistrationForm();
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if (($user = $model->register()) != false) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            $id = implode(',', array_values($user->getPrimaryKey(true)));
            $response->getHeaders()->set('Location', Url::toRoute(['view', 'id' => $id], true));
            return $user;
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
        return $model;
    }

    /**
     * 注册用户
     * @return UserEmailRegistrationForm|\yuncms\user\models\User
     * @throws ServerErrorHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionEmailRegister()
    {
        $model = new UserEmailRegistrationForm();
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if (($user = $model->register()) != false) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            $id = implode(',', array_values($user->getPrimaryKey(true)));
            $response->getHeaders()->set('Location', Url::toRoute(['view', 'id' => $id], true));
            return $user;
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
        return $model;
    }

    /**
     * 修改密码
     * @return UserSettingsForm
     * @throws ServerErrorHttpException
     */
    public function actionPassword()
    {
        $model = new UserSettingsForm();
        $model->load(Yii::$app->request->post(), '');
        if ($model->save()) {
            Yii::$app->getResponse()->setStatusCode(200);
            return $model;
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
        return $model;
    }

    /**
     * 重置密码
     * @return UserRecoveryForm
     * @throws ServerErrorHttpException
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function actionRecovery()
    {
        $model = new UserRecoveryForm();
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($model->resetPassword()) {
            Yii::$app->getResponse()->setStatusCode(200);
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
        return $model;
    }

    /**
     * 上传头像
     * @return AvatarForm|bool
     * @throws ServerErrorHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionAvatar()
    {
        $model = new AvatarForm();
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if (($user = $model->save()) != false) {
            Yii::$app->getResponse()->setStatusCode(200);
            return $user;
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
        return $model;
    }
}