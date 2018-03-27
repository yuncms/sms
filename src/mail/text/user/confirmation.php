<?php

/**
 * @var \yuncms\user\models\User $user
 * @var \yuncms\user\models\UserToken $token
 */
?>
<?= Yii::t('yuncms', 'Hello') ?>,

<?= Yii::t('yuncms', 'Thank you for signing up on {0}', Yii::$app->name) ?>.
<?= Yii::t('yuncms', 'In order to complete your registration, please click the link below') ?>.

<?= $token->url ?>

<?= Yii::t('yuncms', 'If you cannot click the link, please try pasting the text into your browser') ?>.

<?= Yii::t('yuncms', 'If you did not make this request you can ignore this email') ?>.
