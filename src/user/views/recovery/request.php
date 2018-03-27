<?php

use yii\bootstrap\ActiveForm;
use yuncms\helpers\Html;

/* @var yii\web\View $this */
/* @var yuncms\user\models\RecoveryForm $model */
/* @var yii\widgets\ActiveForm $form */

$this->title = Yii::t('yuncms', 'Recover your password');
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-6 col-md-offset-3">
    <h1 class="h4 text-center text-muted"><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin([
        'options' => ['autocomplete' => 'off'],
    ]); ?>

    <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

    <?= Html::submitButton(Yii::t('yuncms', 'Continue'), ['class' => 'btn btn-primary btn-block']) ?><br>

    <?php ActiveForm::end(); ?>
</div>