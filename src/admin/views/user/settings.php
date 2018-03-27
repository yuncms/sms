<?php

use yuncms\helpers\Html;
use yuncms\admin\widgets\Box;
use yuncms\admin\widgets\Toolbar;
use yuncms\admin\widgets\Alert;
use yuncms\admin\widgets\ActiveForm;
use yuncms\admin\models\UserSettings;

/* @var $this yii\web\View */
/* @var $model yuncms\admin\models\UserSettings */

$this->title = Yii::t('yuncms', 'Settings');
$this->params['breadcrumbs'][] = Yii::t('yuncms', 'Manage Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12 authentication-update">
            <?= Alert::widget() ?>
            <?php Box::begin([
                'header' => Html::encode($this->title),
            ]); ?>
            <div class="row">
                <div class="col-sm-4 m-b-xs">
                    <?= Toolbar::widget([
                        'items' => [
                            [
                                'label' => Yii::t('yuncms', 'Manage User'),
                                'url' => ['/admin/user/index'],
                            ],
                            [
                                'label' => Yii::t('yuncms', 'Create User'),
                                'url' => ['/admin/user/create'],
                            ],
                            [
                                'label' => Yii::t('yuncms', 'Settings'),
                                'url' => ['/admin/user/settings'],
                            ],
                        ]
                    ]); ?>
                </div>
                <div class="col-sm-8 m-b-xs">

                </div>
            </div>

            <?php $form = ActiveForm::begin([
                'layout' => 'horizontal'
            ]); ?>

            <?= $form->field($model, 'enableRegistration')->inline()->checkbox([], false); ?>
            <?= $form->field($model, 'enableMobileRegistration')->inline()->checkbox([], false); ?>
            <?= $form->field($model, 'enableRegistrationCaptcha')->inline()->checkbox([], false) ?>
            <?= $form->field($model, 'enableGeneratingPassword')->inline()->checkbox([], false) ?>
            <?= $form->field($model, 'enableConfirmation')->inline()->checkbox([], false) ?>
            <?= $form->field($model, 'enableUnconfirmedLogin')->inline()->checkbox([], false) ?>
            <?= $form->field($model, 'enablePasswordRecovery')->inline()->checkbox([], false) ?>

            <?= $form->field($model, 'emailChangeStrategy')->inline()->dropDownList([
                UserSettings::STRATEGY_INSECURE => Yii::t('yuncms', 'Insecure'),
                UserSettings::STRATEGY_DEFAULT => Yii::t('yuncms', 'Default'),
                UserSettings::STRATEGY_SECURE => Yii::t('yuncms', 'Secure'),
            ], [
                'prompt' => Yii::t('yuncms', 'Please select')
            ]) ?>
            <?= $form->field($model, 'mobileChangeStrategy')->inline()->dropDownList([
                UserSettings::STRATEGY_INSECURE => Yii::t('yuncms', 'Insecure'),
                UserSettings::STRATEGY_DEFAULT => Yii::t('yuncms', 'Default'),
                UserSettings::STRATEGY_SECURE => Yii::t('yuncms', 'Secure'),
            ], [
                'prompt' => Yii::t('yuncms', 'Please select')
            ]) ?>

            <?= $form->field($model, 'avatarPath') ?>
            <?= $form->field($model, 'avatarUrl') ?>


            <?= $form->field($model, 'rememberFor', [
                'inputTemplate' => '<div class="input-group">{input}<span class="input-group-addon">' . Yii::t('yuncms', 'Second') . '</span></div>',
            ])->input('number')->hint(Yii::t('yuncms', 'The time you want the user will be remembered without asking for credentials.')) ?>
            <?= $form->field($model, 'confirmWithin', [
                'inputTemplate' => '<div class="input-group">{input}<span class="input-group-addon">' . Yii::t('yuncms', 'Second') . '</span></div>',
            ])->input('number')->hint(Yii::t('yuncms', 'The time before a confirmation token becomes invalid.')) ?>
            <?= $form->field($model, 'recoverWithin', [
                'inputTemplate' => '<div class="input-group">{input}<span class="input-group-addon">' . Yii::t('yuncms', 'Second') . '</span></div>',
            ])->input('number')->hint(Yii::t('yuncms', 'The time before a recovery token becomes invalid.')) ?>
            <?= $form->field($model, 'cost')->input('number')->hint(Yii::t('yuncms', 'Cost parameter used by the Blowfish hash algorithm.')) ?>

            <?= $form->field($model, 'requestRateLimit', [
                'inputTemplate' => '<div class="input-group">{input}<span class="input-group-addon">' . Yii::t('yuncms', 'Times') . '</span></div>',
            ])->input('number')->hint(Yii::t('yuncms', 'RESTFul The maximum number of requests allowed in one minute.')) ?>

            <?= Html::submitButton(Yii::t('yuncms', 'Settings'), ['class' => 'btn btn-primary']) ?>

            <?php ActiveForm::end(); ?>
            <?php Box::end(); ?>
        </div>
    </div>
</div>