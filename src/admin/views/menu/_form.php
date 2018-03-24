<?php
use yii\helpers\Url;
use yii\helpers\Html;
use xutl\inspinia\ActiveForm;
use yuncms\admin\widgets\IconpIcker;
use xutl\typeahead\Bloodhound;
use xutl\typeahead\TypeAhead;

/* @var \yii\web\View $this */
/* @var \yuncms\admin\models\AdminMenu $model */
/* @var yii\bootstrap\ActiveForm $form */
$engine = new Bloodhound([
    'name' => 'countriesEngine',
    'clientOptions' => [
        'datumTokenizer' => new \yii\web\JsExpression("Bloodhound.tokenizers.obj.whitespace('name')"),
        'queryTokenizer' => new \yii\web\JsExpression("Bloodhound.tokenizers.whitespace"),
        'remote' => [
            'url' => Url::to(['auto-complete', 'query' => 'QRY']),
            'wildcard' => 'QRY'
        ]
    ]
]);


?>

<?php $form = ActiveForm::begin([
    'layout' => 'horizontal'
]); ?>
<?= Html::activeHiddenInput($model, 'parent', ['id' => 'parent_id']); ?>

<?= $form->field($model, 'name')->textInput(['maxlength' => 128]) ?>
<div class="hr-line-dashed"></div>
<?= $form->field($model, 'parent_name')->widget(
    TypeAhead::className(),
    [
        'options' => ['class' => 'form-control'],
        'engines' => [$engine],
        'clientOptions' => [
            'highlight' => true,
            'minLength' => 1,
        ],
        'dataSets' => [
            [
                'name' => 'countries',
                'display' => 'name',
                'source' => $engine->getAdapterScript()
            ]
        ]
    ]
); ?>
<div class="hr-line-dashed"></div>
<?= $form->field($model, 'route')->textInput(['id' => 'route']) ?>
<div class="hr-line-dashed"></div>
<?= $form->field($model, 'visible')->inline(true)->radioList(['1' => Yii::t('app', 'Yes'), '0' => Yii::t('app', 'No')]) ?>
<div class="hr-line-dashed"></div>
<?= $form->field($model, 'sort')->input('number') ?>
<div class="hr-line-dashed"></div>
<?= $form->field($model, 'icon')->widget(IconpIcker::className()) ?>
<div class="hr-line-dashed"></div>
<?= $form->field($model, 'data')->textarea(['rows' => 4]) ?>

<div class="form-group">
    <div class="col-sm-4 col-sm-offset-2">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord
            ? 'btn btn-success' : 'btn btn-primary'])
        ?>
    </div>
</div>
<?php ActiveForm::end(); ?>

