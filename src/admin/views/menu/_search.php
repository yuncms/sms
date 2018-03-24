<?php

use yii\helpers\Html;
use xutl\inspinia\ActiveForm;

/**
 * @var \yii\web\View $this
 * @var \yuncms\admin\models\AdminMenuSearch $model
 * @var ActiveForm $form
 */
?>

<div class="menu-search pull-right">

    <?php $form = ActiveForm::begin([
        'layout' => 'inline',
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id', [
        'inputOptions' => [
            'placeholder' => $model->getAttributeLabel('id'),
        ],
    ]) ?>

    <?= $form->field($model, 'name', [
        'inputOptions' => [
            'placeholder' => $model->getAttributeLabel('name'),
        ],
    ]) ?>

    <?= $form->field($model, 'parent', [
        'inputOptions' => [
            'placeholder' => $model->getAttributeLabel('parent'),
        ],
    ]) ?>

    <?= $form->field($model, 'route', [
        'inputOptions' => [
            'placeholder' => $model->getAttributeLabel('route'),
        ],
    ]) ?>

    <?= $form->field($model, 'data', [
        'inputOptions' => [
            'placeholder' => $model->getAttributeLabel('data'),
        ],
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
