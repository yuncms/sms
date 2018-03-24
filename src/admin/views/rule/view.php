<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use xutl\inspinia\Box;
use xutl\inspinia\Toolbar;
use xutl\inspinia\Alert;

/**
 * @var \yii\web\View $this
 * @var \yuncms\admin\models\AdminAuthItem $model
 */
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin', 'Manage Rule'), 'url' => ['index']];
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
                    <?= Toolbar::widget(['items' =>  [
                        [
                            'label' => Yii::t('admin', 'Manage Rule'),
                            'url' => ['/admin/rule/index'],
                        ],
                        [
                            'label' => Yii::t('admin', 'Create Rule'),
                            'url' => ['/admin/rule/create'],
                        ],
                        [
                            'label' => Yii::t('admin', 'Update Rule'),
                            'url' => ['/admin/rule/update', 'id' => $model->name],
                        ],
                        [
                            'label' => Yii::t('admin', 'Delete Rule'),
                            'url' => ['/admin/rule/delete', 'id' => $model->name],
                            'options' => [
                                'class' => 'btn btn-danger btn-sm',
                                'data' => [
                                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                    'method' => 'post',
                                ],
                            ]
                        ],
                    ]
                    ]); ?>
                </div>
                <div class="col-sm-8 m-b-xs">

                </div>
            </div>
            <?=DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'name',
                    'className',
                ],
            ]);
            ?>
            <?php Box::end(); ?>
        </div>
    </div>
</div>

