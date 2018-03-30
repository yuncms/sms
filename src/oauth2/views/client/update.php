<?php

/*
 * @var yii\web\View $this
 * @var yuncms\oauth2\models\OAuth2Client $model
 */
$this->title = Yii::t('yuncms', 'Update App: ') . ' ' . $model->name;
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('yuncms', 'App Manage'),
    'url' => ['index']
];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->client_id]];
$this->params['breadcrumbs'][] = Yii::t('yuncms', 'Update');
?>
<div class="row">
    <div class="col-md-2">
        <?= $this->render('@yuncms/user/views/_profile_menu') ?>
    </div>
    <div class="col-md-10">
        <h2 class="h3 profile-title"><?= Yii::t('yuncms', 'Update App: ') . ' ' . $model->name ?></h2>
        <div class="row">
            <div class="col-md-12">
                <?= $this->render('_form', ['model' => $model]) ?>
            </div>
        </div>
    </div>
</div>