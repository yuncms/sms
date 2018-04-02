<?php
/* @var $this yii\web\View */

use yuncms\helpers\Html;
use yuncms\widgets\ActiveForm;

print_r($model->getErrors());
?>
<div class="row">
    <div class="col-md-2">
        <?= $this->render('@yuncms/user/views/_profile_menu') ?>
    </div>
    <div class="col-md-10">
        <h2 class="h3 profile-title"><?= Yii::t('yuncms', 'Payment') ?></h2>
        <div class="row">
            <div class="col-md-12">
                <?php $form = ActiveForm::begin(['layout' => 'horizontal', 'enableClientValidation' => true]); ?>
                <?= $form->field($model, 'subject'); ?>
                <?= $form->field($model, 'currency')->inline(true)->radioList(['CNY' => '人民币', 'USD' => '美元']); ?>
                <?= $form->field($model, 'amount'); ?>
                <?= $form->field($model, 'channel'); ?>
                <?= $form->field($model, 'order_no'); ?>
                <?= $form->field($model, 'body'); ?>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-9">
                        <?= Html::submitButton(Yii::t('yuncms', 'Payment'), ['class' => 'btn btn-success']) ?>
                        <br>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
