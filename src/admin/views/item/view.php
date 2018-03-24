<?php

use yii\helpers\Json;
use yii\widgets\DetailView;
use yii\helpers\Html;
use xutl\inspinia\Box;
use xutl\inspinia\Toolbar;
use xutl\inspinia\Alert;

/* @var \yii\web\View $this */
/* @var \yuncms\admin\models\AdminAuthItem $model */
/* @var \yuncms\admin\components\ItemController $context */

$labels = $this->context->labels();
$this->title = $model->name;


$opts = Json::htmlEncode([
    'items' => $model->getItems()
]);
$this->registerJs("var _opts = {$opts};");
$this->registerJs($this->render('_script.js'));
$this->registerCss("
.glyphicon-refresh-animate {
    -animation: spin .7s infinite linear;
    -ms-animation: spin .7s infinite linear;
    -webkit-animation: spinw .7s infinite linear;
    -moz-animation: spinm .7s infinite linear;
}

@keyframes spin {
    from { transform: scale(1) rotate(0deg);}
    to { transform: scale(1) rotate(360deg);}
}
  
@-webkit-keyframes spinw {
    from { -webkit-transform: rotate(0deg);}
    to { -webkit-transform: rotate(360deg);}
}

@-moz-keyframes spinm {
    from { -moz-transform: rotate(0deg);}
    to { -moz-transform: rotate(360deg);}
}
");
$animateIcon = ' <i class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></i>';

if ($labels['Item'] == 'Role') {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('admin', 'Manage Role'), 'url' => ['index']];
    $actions = [
        [
            'label' => Yii::t('admin', 'Manage Role'),
            'url' => ['/admin/role/index'],
        ],
        [
            'label' => Yii::t('admin', 'Create Role'),
            'url' => ['/admin/role/create'],
        ],
        [
            'label' => Yii::t('admin', 'Update Role'),
            'url' => ['/admin/role/update', 'id' => $model->name],
        ],
        [
            'label' => Yii::t('admin', 'Delete Role'),
            'url' => ['/admin/role/delete', 'id' => $model->name],
            'options' => [
                'class' => 'btn btn-danger  btn-sm',
                'data-confirm' => Yii::t('app', 'Are you sure to delete this item?'),
                'data-method' => 'post',
            ],
        ],
    ];
} else {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('admin', 'Manage Permission'), 'url' => ['index']];
    $actions = [
        [
            'label' => Yii::t('admin', 'Manage Permission'),
            'url' => ['/admin/permission/index'],
        ],
        [
            'label' => Yii::t('admin', 'Create Permission'),
            'url' => ['/admin/permission/create'],
        ],
        [
            'label' => Yii::t('admin', 'Update Permission'),
            'url' => ['/admin/permission/update', 'id' => $model->name],
        ],
        [
            'label' => Yii::t('admin', 'Delete Permission'),
            'url' => ['/admin/permission/delete', 'id' => $model->name],
            'options' => [
                'class' => 'btn btn-danger  btn-sm',
                'data-confirm' => Yii::t('app', 'Are you sure to delete this item?'),
                'data-method' => 'post',
            ],
        ],
    ];
}


$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <?= Alert::widget() ?>
            <?php Box::begin([
                'header' => Html::encode($this->title),
            ]); ?>
            <div class="row">
                <div class="col-sm-4 m-b-xs">
                    <?= Toolbar::widget(['items' => $actions]); ?>
                </div>
                <div class="col-sm-8 m-b-xs">

                </div>
            </div>
            <?=
            DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'name',
                    'description:ntext',
                    'ruleName',
                    'data:ntext',
                ],
                'template' => '<tr><th style="width:25%">{label}</th><td>{value}</td></tr>'
            ]);
            ?>
            <div class="row">
                <div class="col-sm-5">
                    <input class="form-control search" data-target="avaliable"
                           placeholder="<?= Yii::t('admin', 'Search for avaliable') ?>">
                    <select multiple size="20" class="form-control list" data-target="avaliable">
                    </select>
                </div>
                <div class="col-sm-2" style="text-align:center">
                    <br><br>
                    <?=
                    Html::a('&gt;&gt;' . $animateIcon, ['assign', 'id' => $model->name], [
                        'class' => 'btn btn-success btn-assign', 'data-target' => 'avaliable'])
                    ?> <br><br>
                    <?=
                    Html::a('&lt;&lt;' . $animateIcon, ['remove', 'id' => $model->name], [
                        'class' => 'btn btn-danger btn-assign', 'data-target' => 'assigned'])
                    ?>
                </div>
                <div class="col-sm-5">
                    <input class="form-control search" data-target="assigned"
                           placeholder="<?= Yii::t('admin', 'Search for assigned') ?>">
                    <select multiple size="20" class="form-control list" data-target="assigned">
                    </select>
                </div>
            </div>

            <?php Box::end(); ?>
        </div>
    </div>
</div>
