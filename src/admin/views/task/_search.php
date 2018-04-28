<?php

use yuncms\helpers\Html;
use yuncms\admin\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model yuncms\admin\models\TaskSearch */
/* @var $form ActiveForm */
?>

<div class="task-search pull-right">

    <?php $form = ActiveForm::begin([
        'layout' => 'inline',
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'route') ?>

    <?php // echo $form->field($model, 'crontab_str') ?>

    <?php // echo $form->field($model, 'switch') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'last_rundate') ?>

    <?php // echo $form->field($model, 'next_rundate') ?>

    <?php // echo $form->field($model, 'execmemory') ?>

    <?php // echo $form->field($model, 'exectime') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('yuncms', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('yuncms', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
