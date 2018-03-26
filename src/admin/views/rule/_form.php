<?php

use yuncms\helpers\Html;
use yuncms\admin\widgets\ActiveForm;

/* @var \yii\web\View $this */
/* @var \yuncms\admin\models\AdminBizRule $model */
/* @var $form ActiveForm */
?>
<?php $form = ActiveForm::begin(['layout'=>'horizontal', ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 64]) ?>
    <div class="hr-line-dashed"></div>
    <?= $form->field($model, 'className')->textInput() ?>
    <div class="hr-line-dashed"></div>

<div class="form-group">
    <div class="col-sm-4 col-sm-offset-2">
        <?php
        echo Html::submitButton($model->isNewRecord ? Yii::t('yuncms', 'Create') : Yii::t('yuncms', 'Update'), [
            'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])
        ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

