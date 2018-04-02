<?php

use yii\helpers\Html;
use yuncms\admin\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model yuncms\admin\models\AdminSearch */
/* @var $form ActiveForm */
?>

<div class="admin-search pull-right">

    <?php $form = ActiveForm::begin([
        'layout' => 'inline',
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'username', [
        'inputOptions' => [
            'placeholder' => $model->getAttributeLabel('username'),
        ],
    ]) ?>

    <?= $form->field($model, 'mobile', [
        'inputOptions' => [
            'placeholder' => $model->getAttributeLabel('mobile'),
        ],
    ]) ?>

    <?= $form->field($model, 'email', [
        'inputOptions' => [
            'placeholder' => $model->getAttributeLabel('email'),
        ],
    ]) ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('yuncms', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('yuncms', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
