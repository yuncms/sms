<?php
/**
 * @var \yuncms\user\models\User $user
 * @var \yuncms\user\models\UserToken $token
 */
?>
<?= Yii::t('yuncms', 'Hello') ?>,

<?= Yii::t('yuncms', 'Your account on {0} has been created.', Yii::$app->name) ?>.
<?php if (Yii::$app->settings->get('enableGeneratingPassword','user')): ?>
    <?= Yii::t('yuncms', 'We have generated a password for you') ?>:
    <?= $user->password ?>
<?php endif ?>

<?php if ($token !== null): ?>
    <?= Yii::t('yuncms', 'In order to complete your registration, please click the link below') ?>.

    <?= $token->url ?>

    <?= Yii::t('yuncms', 'If you cannot click the link, please try pasting the text into your browser') ?>.
<?php endif ?>

<?= Yii::t('yuncms', 'If you did not make this request you can ignore this email') ?>.
