<?php

use yii\bootstrap\ActiveForm;
use yuncms\helpers\Html;
use yuncms\user\models\ResendForm;

/* @var yii\web\View $this */
/* @var ResendForm $model */
/* @var ActiveForm $form */

$this->title = Yii::t('yuncms', 'Request new confirmation message');
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-6 col-md-offset-3">
    <h1 class="h4 text-center text-muted"><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin([
        'options' => ['autocomplete' => 'off'],
        'enableAjaxValidation' => true,
        'enableClientValidation' => false,
    ]); ?>
    <?= $form->field($model, 'email', ['inputOptions' => ['autocomplete' => 'off', 'required' => true]])->textInput(['autofocus' => true]) ?>

    <?= Html::submitButton(Yii::t('yuncms', 'Continue'), ['class' => 'btn btn-primary btn-block']) ?>

    <?php ActiveForm::end(); ?>
</div>
