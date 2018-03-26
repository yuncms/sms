<?php
/**
 * @var yii\web\View
 * @var yuncms\models\User $model
 */
?>

<?php $this->beginContent('@yuncms/admin/views/user/update.php', ['model' => $model]) ?>

<table class="table">
    <tr>
        <td><strong><?= Yii::t('yuncms', 'Registration time') ?>:</strong></td>
        <td><?= Yii::t('yuncms', '{0, date, MMMM dd, YYYY HH:mm}', [$model->created_at]) ?></td>
    </tr>
    <?php if ($model->registration_ip !== null): ?>
        <tr>
            <td><strong><?= Yii::t('yuncms', 'Registration IP') ?>:</strong></td>
            <td><?= $model->registration_ip ?></td>
        </tr>
    <?php endif ?>
    <tr>
        <td><strong><?= Yii::t('yuncms', 'Email Confirmation status') ?>:</strong></td>
        <?php if ($model->isEmailConfirmed): ?>
            <td class="text-success"><?= Yii::t('yuncms', 'Confirmed at {0, date, MMMM dd, YYYY HH:mm}', [$model->email_confirmed_at]) ?></td>
        <?php else: ?>
            <td class="text-danger"><?= Yii::t('yuncms', 'Unconfirmed') ?></td>
        <?php endif ?>
    </tr>
    <tr>
        <td><strong><?= Yii::t('yuncms', 'Mobile Confirmation status') ?>:</strong></td>
        <?php if ($model->isMobileConfirmed): ?>
            <td class="text-success"><?= Yii::t('yuncms', 'Confirmed at {0, date, MMMM dd, YYYY HH:mm}', [$model->mobile_confirmed_at]) ?></td>
        <?php else: ?>
            <td class="text-danger"><?= Yii::t('yuncms', 'Unconfirmed') ?></td>
        <?php endif ?>
    </tr>
    <tr>
        <td><strong><?= Yii::t('yuncms', 'Block status') ?>:</strong></td>
        <?php if ($model->isBlocked): ?>
            <td class="text-danger"><?= Yii::t('yuncms', 'Blocked at {0, date, MMMM dd, YYYY HH:mm}', [$model->blocked_at]) ?></td>
        <?php else: ?>
            <td class="text-success"><?= Yii::t('yuncms', 'Not blocked') ?></td>
        <?php endif ?>
    </tr>
</table>

<?php $this->endContent() ?>
