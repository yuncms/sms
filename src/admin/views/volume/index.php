<?php

use yii\web\View;
use yii\helpers\Url;
use yuncms\helpers\Html;
use yuncms\admin\widgets\Box;
use yuncms\admin\widgets\Toolbar;
use yuncms\admin\widgets\Alert;
use yuncms\grid\GridView;
use yii\widgets\Pjax;
use yuncms\models\Volume;

/* @var $this yii\web\View */
/* @var $searchModel yuncms\admin\models\VolumeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('yuncms', 'Manage Volume');
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs("jQuery(\"#batch_deletion\").on(\"click\", function () {
    yii.confirm('" . Yii::t('yuncms', 'Are you sure you want to delete this item?') . "',function(){
        var ids = jQuery('#gridview').yiiGridView(\"getSelectedRows\");
        jQuery.post(\"/volume/batch-delete\",{ids:ids});
    });
});", View::POS_LOAD);
?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12 volume-index">
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
                            'label' => Yii::t('yuncms', 'Manage Volume'),
                            'url' => ['index'],
                        ],
                        [
                            'label' => Yii::t('yuncms', 'Create Volume'),
                            'url' => ['create'],
                        ],
                        [
                            'options' => ['id' => 'batch_deletion', 'class' => 'btn btn-sm btn-danger'],
                            'label' => Yii::t('yuncms', 'Batch Deletion'),
                            'url' => 'javascript:void(0);',
                        ]
                    ]]); ?>
                </div>
                <div class="col-sm-8 m-b-xs">
                    <?= $this->render('_search', ['model' => $searchModel]); ?>
                </div>
            </div>
            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'options' => ['id' => 'gridview'],
                'layout' => "{items}\n{summary}\n{pager}",
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'class' => 'yii\grid\CheckboxColumn',
                        "name" => "id",
                    ],
                    //['class' => 'yii\grid\SerialColumn'],
                    'id',
                    'identity',
                    'name',
                    'pub:boolean',
                    [
                        'attribute' => 'status',
                        'value' => function ($model) {
                            return $model->status == Volume::STATUS_ACTIVE ? Yii::t('yuncms', 'Active') : Yii::t('yuncms', 'Disable');
                        },
                        'label' => Yii::t('yuncms', 'Status'),
                        'filter' => [
                            Volume::STATUS_ACTIVE => Yii::t('yuncms', 'Active'),
                            Volume::STATUS_DISABLED => Yii::t('yuncms', 'Disable')
                        ]
                    ],
                    [
                        'attribute' => 'created_at',
                        'format' => 'datetime',
                        'filter' => \yii\jui\DatePicker::widget([
                            'model' => $searchModel,
                            'options' => [
                                'class' => 'form-control'
                            ],
                            'attribute' => 'created_at',
                            'name' => 'updated_at',
                            'dateFormat' => 'yyyy-MM-dd'
                        ]),
                    ],
                    // 'updated_at',
                    [
                        'class' => 'yuncms\grid\ActionColumn',
                        'template' => '{configuration} {view} {update} {delete}',
                        'buttons' => ['configuration' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-cog"></span>',
                                Url::toRoute(['configuration', 'id' => $model->id]), [
                                    'title' => Yii::t('yuncms', 'Configuration'),
                                    'aria-label' => Yii::t('yuncms', 'Cssignment'),
                                    'data-pjax' => '0',
                                    'class' => 'btn btn-sm btn-default',
                                ]);
                        }]
                    ],
                ],
            ]); ?>
            <?php Box::end(); ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>
