<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yuncms\helpers\Html;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var yuncms\user\models\LoginForm $model
 * @var string $action
 */
?>

<?php if (Yii::$app->getUser()->getIsGuest()): ?>

    <?php $form = ActiveForm::begin([
        'id' => 'login-widget-form',
        'fieldConfig' => [
            'template' => "{input}\n{error}",
        ],
        'action' => Url::to(['/user/security/login']),
    ]) ?>

    <?= $form->field($model, 'login')->textInput(['placeholder' => 'Login']) ?>

    <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Password']) ?>

    <?= $form->field($model, 'rememberMe')->checkbox() ?>

    <?= Html::submitButton(Yii::t('yuncms', 'Sign in'), ['class' => 'btn btn-primary btn-block']) ?>

    <?php ActiveForm::end(); ?>

<?php else: ?>

    <?= Html::a(Yii::t('yuncms', 'Logout'), ['/user/security/logout'], ['class' => 'btn btn-danger btn-block', 'data-method' => 'post']) ?>

<?php endif ?>
