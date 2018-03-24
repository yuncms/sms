<?php

use yii\helpers\Html;
use yii\helpers\Json;
use xutl\inspinia\Box;
use xutl\inspinia\Toolbar;
use xutl\inspinia\Alert;

/* @var \yii\web\View $this */
$this->title = Yii::t('admin', 'Manage Route');
$this->params['breadcrumbs'][] = $this->title;

$opts = Json::htmlEncode([
    'routes' => $routes
]);
$this->registerJs("var _opts = {$opts};");
$this->registerJs($this->render('_script.js'));
$this->registerCss("
.glyphicon-refresh-animate {
    -animation: spin .7s infinite linear;
    -ms-animation: spin .7s infinite linear;
    -webkit-animation: spinw .7s infinite linear;
    -moz-animation: spinm .7s infinite linear;
}

@keyframes spin {
    from { transform: scale(1) rotate(0deg);}
    to { transform: scale(1) rotate(360deg);}
}
  
@-webkit-keyframes spinw {
    from { -webkit-transform: rotate(0deg);}
    to { -webkit-transform: rotate(360deg);}
}

@-moz-keyframes spinm {
    from { -moz-transform: rotate(0deg);}
    to { -moz-transform: rotate(360deg);}
}
");
$animateIcon = ' <i class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></i>';
?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <?= Alert::widget() ?>
            <?php Box::begin([
                'header' => Html::encode($this->title),
            ]); ?>
            <div class="widget-body-toolbar">
                <div class="input-group">
                    <input id="inp-route" type="text" class="form-control"
                           placeholder="<?= Yii::t('admin', 'New route(s)') ?>">
                    <span class="input-group-btn">
                        <?= Html::a(Yii::t('admin', 'Add') . $animateIcon, ['create'], ['class' => 'btn btn-success', 'id' => 'btn-new']) ?>
                    </span>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-5">
                    <div class="input-group">
                        <input class="form-control search" data-target="avaliable"
                               placeholder="<?= Yii::t('admin', 'Search for avaliable') ?>">
                        <span class="input-group-btn">
                            <?= Html::a('<span class="glyphicon glyphicon-refresh"></span>', ['refresh'], ['class' => 'btn btn-default', 'id' => 'btn-refresh']) ?>
                        </span>
                    </div>
                    <select multiple size="20" class="form-control list" data-target="avaliable">
                    </select>
                </div>
                <div class="col-sm-2" style="text-align:center">
                    <div>
                        <br><br>
                        <?=
                        Html::a('&gt;&gt;' . $animateIcon, ['assign'], [
                            'class' => 'btn btn-success btn-assign', 'data-target' => 'avaliable'])
                        ?><br><br>
                        <?=
                        Html::a('&lt;&lt;' . $animateIcon, ['remove'], [
                            'class' => 'btn btn-danger btn-assign', 'data-target' => 'assigned'])
                        ?>
                    </div>

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
