<?php

use yii\helpers\Url;
use yuncms\admin\helpers\Html;
use yii\widgets\Pjax;
use yuncms\admin\grid\TreeGrid;
use xutl\inspinia\Box;
use xutl\inspinia\Toolbar;
use xutl\inspinia\Alert;

/* @var \yii\web\View $this */
/* @var \yii\data\ActiveDataProvider $dataProvider */
/* @var \yuncms\admin\models\AdminMenuSearch $searchModel */

$this->title = Yii::t('admin', 'Manage Menu');
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
                    <?= Toolbar::widget(['items' => [
                        [
                            'label' => Yii::t('admin', 'Manage Menu'),
                            'url' => ['/admin/menu/index'],
                        ],
                        [
                            'label' => Yii::t('admin', 'Create Menu'),
                            'url' => ['/admin/menu/create'],
                        ],
                    ]]); ?>
                </div>
                <div class="col-sm-8 m-b-xs">

                </div>
            </div>
            <?php Pjax::begin(); ?>
            <?= TreeGrid::widget([
                'dataProvider' => $dataProvider,
                'keyColumnName' => 'id',
                'parentColumnName' => 'parent',
                'parentRootValue' => null, //first parentId value
                'pluginOptions' => [
                    'initialState' => 'collapse',
                ],
                'columns' => [
                    'name',
                    'route',
                    [
                        'attribute' => 'icon',
                        'value' => function($model) {
                           return Html::icon($model->icon);
                        },
                        'format' => 'raw'
                    ],
                    [
                        'class' => 'yuncms\admin\grid\PositionColumn',
                        'attribute' => 'sort'
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => Yii::t('app', 'Operation'),
                        'template' => '{add} {view} {update} {delete}',
                        'buttons' => ['add' => function ($url, $model, $key) {
                            return Html::a('<span class="fa fa-plus"></span>',
                                Url::toRoute(['/admin/menu/create', 'parent' => $model->id]), [
                                    'title' => Yii::t('admin', 'Add subMenu'),
                                    'aria-label' => Yii::t('admin', 'Add subMenu'),
                                    'data-pjax' => '0',
                                ]);
                        }]
                    ]
                ],
            ]); ?>
            <?php Pjax::end(); ?>
            <?php Box::end(); ?>
        </div>
    </div>
</div>