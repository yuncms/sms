<?php

use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use xutl\inspinia\Box;
use xutl\inspinia\Toolbar;
use xutl\inspinia\Alert;

/* @var yii\web\View $this */
/* @var \yuncms\admin\models\AdminAssignment $model */
/* @var string $fullnameField */

$userName = $model->{$usernameField};
if (!empty($fullnameField)) {
    $userName .= ' (' . ArrayHelper::getValue($model, $fullnameField) . ')';
}
$userName = Html::encode($userName);

$this->title = Yii::t('admin', 'Assignment') . ' : ' . $userName;
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin', 'Manage Assignment'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $userName;

$opts = Json::htmlEncode([
    'items' => $model->getItems()
]);
$this->registerJs("var _opts = {$opts};");
$this->registerJs($this->render('_script.js'));
$animateIcon = ' <i class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></i>';
?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12 assignment-view">
            <?= Alert::widget() ?>
            <?php Box::begin([
                'header' => Html::encode($this->title),
            ]); ?>
            <div class="row">
                <div class="col-sm-4 m-b-xs">
                    <?= Toolbar::widget(['items' => [
                        [
                            'label' => Yii::t('admin', 'Manage Assignment'),
                            'url' => ['/admin/assignment/index'],
                        ],
                        [
                            'label' => Yii::t('admin', 'Create'),
                            'url' => ['/user/user/create'],
                        ],
                    ]]); ?>
                </div>
                <div class="col-sm-8 m-b-xs">

                </div>
            </div>

            <div class="row">
                <div class="col-sm-5">
                    <input class="form-control search" data-target="avaliable"
                           placeholder="<?= Yii::t('admin', 'Search for avaliable') ?>">
                    <select multiple size="20" class="form-control list" data-target="avaliable">
                    </select>
                </div>
                <div class="col-sm-2" style="text-align:center">
                    <br>
                    <br>
                    <?=
                    Html::a('&gt;&gt;' . $animateIcon, ['assign', 'id' => (string)$model->id], [
                        'class' => 'btn btn-success btn-assign', 'data-target' => 'avaliable'])
                    ?>
                    <br>
                    <br>
                    <?=
                    Html::a('&lt;&lt;' . $animateIcon, ['revoke', 'id' => (string)$model->id], [
                        'class' => 'btn btn-danger btn-assign', 'data-target' => 'assigned'])
                    ?>
                </div>
                <div class="col-sm-5">
                    <input class="form-control search" data-target="assigned"
                           placeholder="<?= Yii::t('admin', 'Search for assigned') ?>">
                    <select multiple size="20" class="form-control list" data-target="assigned">
                    </select>
                </div>
            </div>
            <?php Box::end(); ?>
        </div>
    </div>
</div>
