<?php
use yuncms\helpers\Html;
use yuncms\admin\widgets\Box;
use yuncms\admin\widgets\Toolbar;
use yuncms\admin\widgets\Alert;

/**
 * @var yii\web\View $this
 * @var yuncms\user\models\User $model
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
                <?= Yii::t('yuncms', 'Credentials will be sent to the user by email') ?>.
                <?= Yii::t('yuncms', 'A password will be generated automatically if not provided') ?>.
            </div>
            <?= $this->render('_form', ['model' => $model]) ?>
            <?php Box::end(); ?>
        </div>
    </div>
</div>