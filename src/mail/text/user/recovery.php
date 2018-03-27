<?php
/**
 * @var \yuncms\user\models\User $user
 * @var \yuncms\user\models\UserToken $token
 */
?>
<?= Yii::t('yuncms', 'Hello') ?>,

<?= Yii::t('yuncms', 'We have received a request to reset the password for your account on {0}', Yii::$app->name) ?>.
<?= Yii::t('yuncms', 'Please click the link below to complete your password reset') ?>.

<?= $token->url ?>

<?= Yii::t('yuncms', 'If you cannot click the link, please try pasting the text into your browser') ?>.

<?= Yii::t('yuncms', 'If you did not make this request you can ignore this email') ?>.
