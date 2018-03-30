<?php


use yii\widgets\DetailView;
use yuncms\helpers\Html;
use yuncms\admin\widgets\Box;
use yuncms\admin\widgets\Toolbar;
use yuncms\admin\widgets\Alert;

/* @var $this yii\web\View */
/* @var $model yuncms\oauth2\models\OAuth2Client */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('yuncms', 'Manage Client'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12 client-view">
            <?= Alert::widget() ?>
            <?php Box::begin([
                'header' => Html::encode($this->title),
            ]); ?>
            <div class="row">
                <div class="col-sm-4 m-b-xs">
                    <?= Toolbar::widget(['items' => [
                        [
                            'label' => Yii::t('yuncms', 'Manage Client'),
                            'url' => ['index'],
                        ],
//                        [
//                            'label' => Yii::t('oauth2', 'Create Client'),
//                            'url' => ['create'],
//                        ],
                        [
                            'label' => Yii::t('yuncms', 'Update Client'),
                            'url' => ['update', 'id' => $model->client_id],
                            'options' => ['class' => 'btn btn-primary btn-sm']
                        ],
                        [
                            'label' => Yii::t('yuncms', 'Delete Client'),
                            'url' => ['delete', 'id' => $model->client_id],
                            'options' => [
                                'class' => 'btn btn-danger btn-sm',
                                'data' => [
                                    'confirm' => Yii::t('yuncms', 'Are you sure you want to delete this item?'),
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
                    'client_id',
                    'client_secret',
                    'user_id',
                    'redirect_uri:url',
                    [
                        'label' => Yii::t('yuncms', 'Grant type'),
                        'value' => function ($model) {
                            if (empty($model->grant_type)) {
                                return Yii::t('yuncms', 'All Type');
                            }
                            return $model->grant_type;
                        }
                    ],
                    'scope:ntext',
                    'name',
                    'domain',
                    'provider',
                    'icp',
                    'registration_ip',
                    'created_at:datetime',
                    'updated_at:datetime',
                ],
            ]) ?>
            <?php Box::end(); ?>
        </div>
    </div>
</div>

