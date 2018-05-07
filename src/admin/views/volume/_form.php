<?php

use yuncms\helpers\Html;
use yuncms\admin\widgets\ActiveForm;
use yuncms\models\Volume;

/* @var \yii\web\View $this */
/* @var yuncms\models\Volume $model */
/* @var ActiveForm $form */
?>
<?php $form = ActiveForm::begin(['layout' => 'horizontal', 'enableAjaxValidation' => true, 'enableClientValidation' => false,]); ?>

<?= $form->field($model, 'identity')->textInput(['maxlength' => true]) ?>
<div class="hr-line-dashed"></div>

<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
<div class="hr-line-dashed"></div>

<?= $form->field($model, 'className')->textInput(['maxlength' => true]) ?>
<div class="hr-line-dashed"></div>

<?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>
<div class="hr-line-dashed"></div>

<?= $form->field($model, 'pub')->inline(true)->boolean() ?>
<div class="hr-line-dashed"></div>

<?= $form->field($model, 'status')->inline()->radioList([
    Volume::STATUS_ACTIVE => Yii::t('yuncms', 'Active'),
    Volume::STATUS_DISABLED => Yii::t('yuncms', 'Disable')
]) ?>
<div class="hr-line-dashed"></div>


<div class="form-group">
    <div class="col-sm-4 col-sm-offset-2">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('yuncms', 'Create') : Yii::t('yuncms', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

