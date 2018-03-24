<?php

use yii\widgets\DetailView;
use yii\helpers\Html;
use xutl\inspinia\Box;
use xutl\inspinia\Toolbar;
use xutl\inspinia\Alert;
use yuncms\admin\models\Admin;

/* @var \yii\web\View $this */
/* @var \yuncms\admin\models\Admin $model */

$this->title = $model->username;
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('admin', 'Manage Admin'),
    'url' => ['index']
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12 ban-word-view">
            <?= Alert::widget() ?>
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
                        [
                            'label' => Yii::t('admin', 'Update Admin'),
                            'url' => ['update', 'id' => $model->id],
                        ],
                        [
                            'label' => Yii::t('admin', 'Delete Admin'),
                            'url' => ['delete', 'id' => $model->id],
                            'options' => [
                                'class' => 'btn btn-danger btn-sm',
                                'data' => [
                                    'confirm' => Yii::t('admin', 'Are you sure you want to delete this item?'),
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
                    'username',
                    'mobile',
                    'email:email',
                    [
                        'value' => $model->status == Admin::STATUS_ACTIVE ? Yii::t('admin', 'Active') : Yii::t('admin', 'Disable'),
                        'label' => Yii::t('admin', 'Status'),
                    ],
                    'last_login_at:datetime',
                    'created_at:datetime',
                    'updated_at:datetime',
                ],
            ]) ?>
            <?php Box::end(); ?>
        </div>
    </div>
</div>