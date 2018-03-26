<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yuncms\admin\components\RouteRule;
use xutl\inspinia\Box;
use xutl\inspinia\Toolbar;
use xutl\inspinia\Alert;

/* @var \yii\web\View $this */
/* @var \yii\data\ActiveDataProvider $dataProvider */
/* @var \yuncms\admin\models\AdminAuthItemSearch $searchModel */
/* @var \yuncms\admin\components\ItemController $context */

$labels = $this->context->labels();
if ($labels['Item'] == 'Role') {
    $this->title = Yii::t('yuncms', 'Manage Role');
    $actions = [
        [
            'label' => Yii::t('yuncms', 'Manage Role'),
            'url' => ['/admin/role/index'],
        ],
        [
            'label' => Yii::t('yuncms', 'Create Role'),
            'url' => ['/admin/role/create'],
        ],
    ];
} else {
    $this->title = Yii::t('yuncms', 'Manage Permission');
    $actions = [
        [
            'label' => Yii::t('yuncms', 'Manage Permission'),
            'url' => ['/admin/permission/index'],
        ],
        [
            'label' => Yii::t('yuncms', 'Create Permission'),
            'url' => ['/admin/permission/create'],
        ],
    ];
}
$this->params['breadcrumbs'][] = $this->title;

$rules = array_keys(Yii::$app->getAuthManager()->getRules());
$rules = array_combine($rules, $rules);
unset($rules[RouteRule::RULE_NAME]);

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
            <?= GridView::widget([
                'options' => ['id' => 'gridview'],
                'layout' => "{items}\n{summary}\n{pager}",
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'name',
                        'label' => Yii::t('yuncms', 'Role Name'),
                    ],
                    [
                        'attribute' => 'ruleName',
                        'label' => Yii::t('yuncms', 'Rule Name'),
                        'filter' => $rules
                    ],
                    [
                        'attribute' => 'description',
                        'label' => Yii::t('yuncms', 'Role Description'),
                    ],
                    ['class' => 'yii\grid\ActionColumn',],
                ],
            ])
            ?>
            <?php Box::end(); ?>
        </div>
    </div>
</div>