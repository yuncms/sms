<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\controllers;

use Yii;
use yii\widgets\ActiveForm;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yuncms\web\Response;
use yuncms\web\Controller;
use yuncms\admin\models\UserSettings;
use yuncms\tag\models\Tag;
use yuncms\models\Settings;
use yuncms\user\models\User;
use yuncms\user\models\UserProfile;
use yuncms\user\models\UserSocialAccount;
use yuncms\user\models\AvatarForm;
use yuncms\user\models\SettingsForm;

/**
 * SettingsController manages updating user settings (e.g. profile, email and password).
 *
 * @property \yuncms\user\Module $module
 */
class SettingsController extends Controller
{
    /** @inheritdoc */
    public $defaultAction = 'profile';

    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'disconnect' => ['post'],
                    'follower-tag'=>['post']
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['profile', 'account', 'privacy', 'avatar', 'confirm', 'networks', 'disconnect','follower-tag'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Shows profile settings form.
     * @return array|string|Response
     */
    public function actionProfile()
    {
        $model = UserProfile::findOne(['user_id' => Yii::$app->user->identity->getId()]);
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('yuncms', 'Your profile has been updated'));
            return $this->refresh();
        }
        return $this->render('profile', [
            'model' => $model,
        ]);
    }

    /**
     * Show portrait setting form
     * @return \yii\web\Response|string
     * @throws \League\Flysystem\FileExistsException
     * @throws \League\Flysystem\FileNotFoundException
     * @throws \yii\base\ErrorException
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function actionAvatar()
    {
        $model = new AvatarForm();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('yuncms', 'Your avatar has been updated'));
        }
        return $this->render('avatar', [
            'model' => $model,
        ]);
    }

    /**
     * Displays page where user can update account settings (username, email or password).
     * @return array|string|Response
     */
    public function actionAccount()
    {
        /** @var SettingsForm $model */
        $model = new SettingsForm();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('yuncms', 'Your account details have been updated.'));
            return $this->refresh();
        }
        return $this->render('account', [
            'model' => $model,
        ]);
    }

    /**
     * 关注某tag
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionFollowerTag()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $tagId = Yii::$app->request->post('tag_id', null);
        if (($tag = Tag::findOne($tagId)) == null) {
            throw new NotFoundHttpException ();
        } else {
            /** @var \yuncms\user\models\User $user */
            $user = Yii::$app->user->identity;
            if ($user->hasTagValues($tag->id)) {
                $user->removeTagValues($tag->id);
                $user->save();
                return ['status' => 'unFollowed'];
            } else {
                $user->addTagValues($tag->id);
                $user->save();
                return ['status' => 'followed'];
            }
        }
    }

    /**
     * Attempts changing user's email address.
     *
     * @param integer $id
     * @param string $code
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionConfirm($id, $code)
    {
        $user = User::findOne($id);
        if ($user === null || Yii::$app->settings->get('emailChangeStrategy','user') == UserSettings::STRATEGY_INSECURE) {
            throw new NotFoundHttpException();
        }
        $user->attemptEmailChange($code);
        return $this->redirect(['account']);
    }

    /**
     * Displays list of connected network accounts.
     *
     * @return string
     */
    public function actionNetworks()
    {
        return $this->render('networks', [
            'user' => Yii::$app->user->identity,
        ]);
    }

    /**
     * Disconnects a network account from user.
     *
     * @param integer $id
     *
     * @return \yii\web\Response
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDisconnect($id)
    {
        $account = UserSocialAccount::find()->byId($id)->one();
        if ($account === null) {
            Yii::$app->session->setFlash('success', Yii::t('yuncms', 'Your account have been updated.'));
            return $this->redirect(['networks']);
        }
        if ($account->user_id != Yii::$app->user->id) {
            Yii::$app->session->setFlash('success', Yii::t('yuncms', 'You do not have the right to dismiss this social account.'));
            return $this->redirect(['networks']);
        }
        $account->delete();
        Yii::$app->session->setFlash('success', Yii::t('yuncms', 'Your account have been updated.'));
        return $this->redirect(['networks']);
    }
}
