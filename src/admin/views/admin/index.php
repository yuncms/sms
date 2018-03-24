<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use xutl\inspinia\Box;
use xutl\inspinia\Toolbar;
use xutl\inspinia\Alert;
use yuncms\admin\models\Admin;

/* @var $this */
/* @var \yuncms\admin\models\AdminSearch $searchModel */
/* @var \yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('admin', 'Manage Admin');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12 ban-word-index">
            <?= Alert::widget() ?>
            <?php Pjax::begin(); ?>
            <?php Box::begin([
                //'noPadding' => true,
                'header' => Html::encode($this->title),
            ]); ?>
            <div class="row">
                <div class="col-sm-4 m-b-xs">
                    <?= Toolbar::widget(['items' => [
                        [
                            'label' => Yii::t('admin', 'Manage Admin'),
                            'url' => ['index'],
                        ],
                        [
                            'label' => Yii::t('admin', 'Create Admin'),
                            'url' => ['create'],
                        ],
                    ]]); ?>
                </div>
                <div class="col-sm-8 m-b-xs">
                    <?= $this->render('_search', ['model' => $searchModel]); ?>
                </div>
            </div>
            <?= GridView::widget([
                'options' => ['id' => 'gridview'],
                'layout' => "{items}\n{summary}\n{pager}",
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn', 'header' => 'ID'],
                    'username',
                    'mobile',
                    'email:email',
                    [
                        'attribute' => 'status',
                        'value' => function ($model) {
                            return $model->status == Admin::STATUS_ACTIVE ? Yii::t('admin', 'Active') : Yii::t('admin', 'Disable');
                        },
                        'label' => Yii::t('admin', 'Status'),
                    ],
                    'last_login_at:datetime',
                    'created_at:datetime',
                    'updated_at:datetime',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => Yii::t('admin', 'Operation'),
                        'template' => '{assignment} {view} {update} {delete}',
                        'buttons' => ['assignment' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-dashboard"></span>',
                                Url::toRoute(['assignment/view', 'id' => $model->id]), [
                                    'title' => Yii::t('admin', 'Assignment'),
                                    'aria-label' => Yii::t('admin', 'Assignment'),
                                    'data-pjax' => '0',
                                ]);
                        }]
                    ]
                ],
            ]); ?>
            <?php Box::end(); ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>