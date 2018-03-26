<?php
use yii\bootstrap\Nav;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use xutl\inspinia\Box;
use xutl\inspinia\Toolbar;
use xutl\inspinia\Alert;

/**
 * @var yii\web\View $this
 * @var yuncms\models\User $model
 */

$this->title = Yii::t('yuncms', 'Create a user account');
$this->params['breadcrumbs'][] = ['label' => Yii::t('yuncms', 'Users'), 'url' => ['index']];
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
                    <?= Toolbar::widget(['items' => [
                        [
                            'label' => Yii::t('yuncms', 'Manage Users'),
                            'url' => ['/admin/user/index'],
                        ],
                        [
                            'label' => Yii::t('yuncms', 'Create User'),
                            'url' => ['/admin/user/create'],
                        ],
                    ]]); ?>
                </div>
                <div class="col-sm-8 m-b-xs">

                </div>
            </div>

            <div class="alert alert-info">
                <?= Yii::t('user', 'Credentials will be sent to the user by email') ?>.
                <?= Yii::t('user', 'A password will be generated automatically if not provided') ?>.
            </div>
            <?= $this->render('_form', ['model' => $model]) ?>
            <?php Box::end(); ?>
        </div>
    </div>
</div>