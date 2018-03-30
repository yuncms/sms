<?php
use yii\bootstrap\ActiveForm;
use yuncms\helpers\Html;

/* @var $this yii\web\View */
/* @var $model yuncms\oauth2\models\OAuth2Client */
/* @var $form yii\widgets\ActiveForm */
?>


<?php $form = ActiveForm::begin([
    'layout' => 'horizontal',
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
    'validateOnBlur' => false,
]); ?>
<?= $form->field($model, 'name') ?>
<?= $form->field($model, 'domain') ?>
<?= $form->field($model, 'provider') ?>
<?= $form->field($model, 'icp') ?>
<?= $form->field($model, 'grant_type')->dropDownList(['authorization_code' => 'Authorization Code', 'password' => 'Password'], [
    'prompt' => Yii::t('yuncms', 'All Type')
]); ?>
<?= $form->field($model, 'redirect_uri'); ?>

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('yuncms', 'Create') : Yii::t('yuncms', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>
<?php ActiveForm::end(); ?>