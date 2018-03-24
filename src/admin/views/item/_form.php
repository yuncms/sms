<?php

use yii\helpers\Html;
use xutl\inspinia\ActiveForm;
use yuncms\admin\components\RouteRule;

/* @var yii\web\View $this */
/* @var yuncms\admin\models\AdminAuthItem $model */
/* @var yuncms\admin\widgets\ActiveForm $form */
/* @var yuncms\admin\components\ItemController $context */

$labels = $this->context->labels();
$rules = array_keys(Yii::$app->getAuthManager()->getRules());
$rules = array_combine($rules, $rules);
unset($rules[RouteRule::RULE_NAME]);
?>

<?php $form = ActiveForm::begin(['layout' => 'horizontal',]); ?>
<?= $form->field($model, 'name')->textInput(['maxlength' => 64]) ?>
<div class="hr-line-dashed"></div>
<?= $form->field($model, 'description')->textarea(['rows' => 2]) ?>
<div class="hr-line-dashed"></div>
<?= $form->field($model, 'ruleName')->dropDownList($rules, ['prompt' => '--' . Yii::t('admin', 'Select Rule')]) ?>
<div class="hr-line-dashed"></div>
<?= $form->field($model, 'data')->textarea(['rows' => 6]) ?>
<div class="hr-line-dashed"></div>

<div class="form-group">
    <div class="col-sm-4 col-sm-offset-2">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), [
            'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
            'name' => 'submit-button'])
        ?>
    </div>
</div>
<?php ActiveForm::end(); ?>

