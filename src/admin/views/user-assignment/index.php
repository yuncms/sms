<?php

use yii\widgets\Pjax;
use yuncms\grid\GridView;
use yuncms\helpers\Html;
use yuncms\admin\widgets\Box;
use yuncms\admin\widgets\Toolbar;
use yuncms\admin\widgets\Alert;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel \yuncms\admin\models\UserAssignmentSearch */
/* @var $usernameField string */
/* @var $extraColumns string[] */

$this->title = Yii::t('yuncms', 'Manage Assignment');
$this->params['breadcrumbs'][] = $this->title;

$columns = [
    ['class' => 'yii\grid\SerialColumn'],
    $usernameField,
];
if (!empty($extraColumns)) {
    $columns = array_merge($columns, $extraColumns);
}
$columns[] = [
    'class' => 'yuncms\grid\ActionColumn',
    'template' => '{view}'
];
?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12 assignment-view">
            <?= Alert::widget() ?>
            <?php Box::begin([
                //'noPadding' => true,
                'header' => Html::encode($this->title),

            ]); ?>
            <div class="row">
                <div class="col-sm-4 m-b-xs">
                    <?= Toolbar::widget(['items' => [
                        [
                            'label' => Yii::t('yuncms', 'Manage User'),
                            'url' => ['/admin/user/index'],
                        ],
                        [
                            'label' => Yii::t('yuncms', 'Create User'),
                            'url' => ['/admin/user/create'],
                        ],
                        [
                            'label' => Yii::t('yuncms', 'Settings'),
                            'url' => ['/admin/user/settings'],
                        ],
                        [
                            'label' => Yii::t('yuncms', 'Manage Assignment'),
                            'url' => ['/admin/user-assignment/index'],
                        ],
                    ]]); ?>
                </div>
                <div class="col-sm-8 m-b-xs">

                </div>
            </div>
            <?php Pjax::begin(); ?>
            <?= GridView::widget([
                'options' => ['id' => 'gridview'],
                'layout' => "{items}\n{summary}\n{pager}",
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => $columns,
            ]);
            ?>
            <?php Pjax::end(); ?>
            <?php Box::end(); ?>
        </div>
    </div>
</div>
