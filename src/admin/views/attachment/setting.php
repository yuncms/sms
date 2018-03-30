<?php

use yuncms\helpers\Html;
use yuncms\admin\widgets\Box;
use yuncms\admin\widgets\Toolbar;
use yuncms\admin\widgets\Alert;
use yuncms\admin\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model yuncms\admin\models\AttachmentSetting */

$this->title = Yii::t('yuncms', 'Settings');
$this->params['breadcrumbs'][] = Yii::t('yuncms', 'Manage Attachment');
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
                                'label' => Yii::t('yuncms', 'Manage Attachment'),
                                'url' => ['index'],
                            ],
                            [
                                'label' => Yii::t('yuncms', 'Settings'),
                                'url' => ['setting'],
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

            <?= $form->field($model, 'volume') ?>
            <?= $form->field($model, 'imageMaxSize') ?>
            <?= $form->field($model, 'imageAllowFiles') ?>
            <?= $form->field($model, 'videoMaxSize') ?>
            <?= $form->field($model, 'videoAllowFiles') ?>
            <?= $form->field($model, 'fileMaxSize') ?>
            <?= $form->field($model, 'fileAllowFiles') ?>

            <?= Html::submitButton(Yii::t('yuncms', 'Settings'), ['class' => 'btn btn-primary']) ?>

            <?php ActiveForm::end(); ?>
            <?php Box::end(); ?>
        </div>
    </div>
</div>