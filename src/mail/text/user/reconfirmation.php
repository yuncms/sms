<?php
/**
 * @var \yuncms\user\models\UserToken $token
 */
?>
<?= Yii::t('yuncms', 'Hello') ?>,

<?= Yii::t('yuncms', 'We have received a request to change the email address for your account on {0}', Yii::$app->name) ?>.
<?= Yii::t('yuncms', 'In order to complete your request, please click the link below') ?>.

<?= $token->url ?>

<?= Yii::t('yuncms', 'If you cannot click the link, please try pasting the text into your browser') ?>.

<?= Yii::t('yuncms', 'If you did not make this request you can ignore this email') ?>.
