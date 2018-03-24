<?php

use yii\helpers\Html;
use yii\captcha\Captcha;
use xutl\inspinia\ActiveForm;
use xutl\inspinia\InspiniaAsset;

/* @var \yii\web\View $this */
/* @var ActiveForm $form */
/* @var \yuncms\admin\models\LoginForm $model */

$asset = InspiniaAsset::register($this);
$this->title = Yii::$app->name . ' - ' . Yii::t('admin', 'Sign in');

//Meta
$this->registerMetaTag(['name' => 'description', 'content' => 'TintSoft Team']);
$this->registerMetaTag(['name' => 'author', 'content' => 'TintSoft Team']);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>

    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= Html::tag('title', Html::encode($this->title)); ?>
    <?= Html::csrfMetaTags() ?>
    <?php $this->head() ?>
</head>
<body class="gray-bg">
<?php $this->beginBody() ?>
<div class="middle-box text-center loginscreen animated fadeInDown">
    <div>
        <div>
            <h1 class="logo-name">Y+</h1>
        </div>
        <h3><?= Yii::t('admin', 'Manage Center'); ?></h3>
        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'options' => [
                'class' => 'm-t'
            ],
        ]); ?>

        <?= $form->field($model, 'login', [
            'inputOptions' => [
                'autofocus' => 'autofocus',
                'autocomplete' => 'off',
                'placeholder' => Yii::t('admin', 'Username')
            ],
            'errorOptions' => ['class' => 'help-block help-block-error full-width','style'=>'text-align:left'],
        ])->label(false); ?>

        <?= $form->field($model, 'password', [
            'inputOptions' => [
                'autocomplete' => 'off',
                'placeholder' => Yii::t('admin', 'Password')
            ],
           'errorOptions' => ['class' => 'help-block help-block-error full-width','style'=>'text-align:left'],
        ])->passwordInput()->label(false) ?>

        <?= $form->field($model, 'verifyCode', [
            'errorOptions' => ['class' => 'help-block help-block-error full-width','style'=>'text-align:left'],
        ])->widget(Captcha::className(), [
            'captchaAction' => '/admin/security/captcha',
            'options' => [
                'class' => 'form-control',
                'autocomplete' => 'off',
                'placeholder' => Yii::t('admin', 'VerifyCode')
            ],
            'template' => '<div class="row"><div class="col-lg-6 col-sm-6 col-xs-6">{input}</div><div class="col-lg-6 col-sm-6 col-xs-6">{image}</div></div>'
        ])->label(false) ?>

        <?= $form->field($model, 'rememberMe')->checkbox([
                'template'=>"<div class=\"checkbox pull-left\">\n{beginLabel}\n{input}\n{labelTitle}\n{endLabel}\n{error}\n{hint}\n</div>",
        ]) ?>


        <?= Html::submitButton(Yii::t('admin', 'Sign in'), ['class' => 'btn btn-primary block full-width m-b']) ?>

        <?php ActiveForm::end(); ?>
        <p class="m-t">
            <small><strong>Copyright</strong> TintSoft Company © 2012-<?= date('Y') ?></small>
        </p>
    </div>
</div>
<?php $this->endBody() ?>
</body>
</html><?php $this->endPage() ?>

