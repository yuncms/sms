<?php

use yuncms\helpers\Html;
use yuncms\admin\widgets\Box;
use yuncms\admin\widgets\Toolbar;
use yuncms\admin\widgets\Alert;

/* @var $this yii\web\View */
/* @var $model yuncms\models\Task */

$this->title = Yii::t('yuncms', 'Update Task') . ': ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('yuncms', 'Manage Task'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('yuncms', 'Update');
?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12 task-update">
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
                    ]]); ?>
                </div>
                <div class="col-sm-8 m-b-xs">

                </div>
            </div>

            <?= $this->render('_form', [
            'model' => $model,
            ]) ?>
            <?php Box::end(); ?>
        </div>
    </div>
</div>