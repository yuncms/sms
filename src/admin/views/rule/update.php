<?php

use yii\helpers\Html;
use xutl\inspinia\Box;
use xutl\inspinia\Toolbar;
use xutl\inspinia\Alert;

/* @var yii\web\View $this   */
/* @var yuncms\admin\models\AdminBizRule $model  */

$this->title = Yii::t('admin', 'Update Rule') . ': ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin', 'Manage Rule'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->name]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
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
                    ]
                    ]); ?>
                </div>
                <div class="col-sm-8 m-b-xs">

                </div>
            </div>
    <?=
    $this->render('_form', [
        'model' => $model,
    ]);
    ?>
    <?php Box::end(); ?>
        </div>
    </div>
</div>
