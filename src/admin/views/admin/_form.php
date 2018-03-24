<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var \yii\web\View $this */
/* @var ActiveForm $form */
?>

<?php $form = ActiveForm::begin(['layout' => 'horizontal']); ?>
<fieldset>
    <?= $form->field($model, 'username') ?>

    <?= $form->field($model, 'mobile')->textInput(); ?>

    <?= $form->field($model, 'email')->input('email'); ?>

    <?= $form->field($model, 'password')->passwordInput() ?>

    <?= $form->field($model, 'status')->inline(true)->radioList([
        '1' => Yii::t('admin', 'Active'),
        '0' => Yii::t('admin', 'Disable')
    ]) ?>

</fieldset>
<div class="form-actions">
    <div class="row">
        <div class="col-md-12">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('admin', 'Create') : Yii::t('admin', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

