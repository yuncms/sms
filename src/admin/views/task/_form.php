<?php

use yuncms\helpers\Html;
use yuncms\admin\widgets\ActiveForm;
use yuncms\models\Task;

/* @var \yii\web\View $this */
/* @var yuncms\models\Task $model */
/* @var ActiveForm $form */
?>
<?php $form = ActiveForm::begin(['layout' => 'horizontal', 'enableAjaxValidation' => true, 'enableClientValidation' => false,]); ?>

<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
<div class="hr-line-dashed"></div>

<?= $form->field($model, 'route')->textInput(['maxlength' => true]) ?>
<div class="hr-line-dashed"></div>

<?= $form->field($model, 'crontab_str')->textInput(['maxlength' => true]) ?>
<div class="hr-line-dashed"></div>

<?= $form->field($model, 'switch')->inline(true)->radioList([
    Task::SWITCH_ACTIVE => Yii::t('yuncms', 'Active'),
    Task::SWITCH_DISABLE => Yii::t('yuncms', 'Disable')
]) ?>
<div class="hr-line-dashed"></div>
<div class="form-group">
    <div class="col-sm-4 col-sm-offset-2">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('yuncms', 'Create') : Yii::t('yuncms', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

