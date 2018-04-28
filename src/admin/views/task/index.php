<?php

use yii\web\View;
use yuncms\helpers\Html;
use yuncms\admin\widgets\Box;
use yuncms\admin\widgets\Toolbar;
use yuncms\admin\widgets\Alert;
use yuncms\grid\GridView;
use yuncms\models\Task;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel yuncms\admin\models\TaskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('yuncms', 'Manage Task');
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs("jQuery(\"#batch_deletion\").on(\"click\", function () {
    yii.confirm('" . Yii::t('yuncms', 'Are you sure you want to delete this item?') . "',function(){
        var ids = jQuery('#gridview').yiiGridView(\"getSelectedRows\");
        jQuery.post(\"/admin/task/batch-delete\",{ids:ids});
    });
});", View::POS_LOAD);
?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12 task-index">
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
                            'label' => Yii::t('yuncms', 'Manage Task'),
                            'url' => ['index'],
                        ],
                        [
                            'label' => Yii::t('yuncms', 'Create Task'),
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
                //'filterModel' => $searchModel,
                'columns' => [
                    [
                        'class' => 'yii\grid\CheckboxColumn',
                        "name" => "id",
                    ],
                    //['class' => 'yii\grid\SerialColumn'],
                    'name',
                    'route',
                    'crontab_str',
                    [
                        'attribute' => 'switch',
                        'value' => function ($model) {
                            return $model->switch == Task::SWITCH_ACTIVE ? Yii::t('yuncms', 'Active') : Yii::t('yuncms', 'Disable');
                        },
                        'label' => Yii::t('yuncms', 'Task Switch'),
                    ],
                    [
                        'attribute' => 'status',
                        'value' => function ($model) {
                            return $model->status == Task::STATUS_NORMAL ? Yii::t('yuncms', 'Normal') : Yii::t('yuncms', 'Saved');
                        },
                        'label' => Yii::t('yuncms', 'Task Status'),
                    ],
                    'last_rundate',
                    'next_rundate',
                    'execmemory',
                    'exectime',
                    [
                        'class' => 'yuncms\grid\ActionColumn',
                        'template' => '{view} {update} {delete}',
                        //'buttons' => [
                        //    'update' => function ($url, $model, $key) {
                        //        return $model->status === 'editable' ? Html::a('Update', $url) : '';
                        //    },
                        //],
                    ],
                ],
            ]); ?>
            <?php Box::end(); ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>
