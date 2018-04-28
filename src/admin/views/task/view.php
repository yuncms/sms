<?php

use yuncms\helpers\Html;
use yuncms\models\Task;
use yuncms\widgets\DetailView;
use yuncms\admin\widgets\Box;
use yuncms\admin\widgets\Toolbar;
use yuncms\admin\widgets\Alert;

/* @var $this yii\web\View */
/* @var $model yuncms\models\Task */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('yuncms', 'Manage Task'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12 task-view">
            <?= Alert::widget() ?>
            <?php Box::begin([
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
                            'label' => Yii::t('yuncms', 'Update Task'),
                            'url' => ['update', 'id' => $model->id],
                            'options' => ['class' => 'btn btn-primary btn-sm']
                        ],
                        [
                            'label' => Yii::t('yuncms', 'Delete Task'),
                            'url' => ['delete', 'id' => $model->id],
                            'options' => [
                                'class' => 'btn btn-danger btn-sm',
                                'data' => [
                                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                    'method' => 'post',
                                ],
                            ]
                        ],
                    ]]); ?>
                </div>
                <div class="col-sm-8 m-b-xs">

                </div>
            </div>

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'name',
                    'route',
                    'crontab_str',
                    [
                        'value' => $model->switch == Task::SWITCH_ACTIVE ? Yii::t('yuncms', 'Active') : Yii::t('yuncms', 'Disable'),
                        'label' => Yii::t('yuncms', 'Task Switch'),
                    ],
                    [
                        'value' => $model->status == Task::STATUS_NORMAL ? Yii::t('yuncms', 'Normal') : Yii::t('yuncms', 'Saved'),
                        'label' => Yii::t('yuncms', 'Task Status'),
                    ],
                    'last_rundate',
                    'next_rundate',
                    'execmemory',
                    'exectime',
                ],
            ]) ?>
            <?php Box::end(); ?>
        </div>
    </div>
</div>

