<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yuncms\assets\AvatarAsset;
use yuncms\assets\FontAwesomeAsset;

/*
 * @var \yii\web\View $this
 * @var \yuncms\user\frontend\models\AvatarForm $model
 */
FontAwesomeAsset::register($this);
AvatarAsset::register($this);
$this->title = Yii::t('yuncms', 'My Avatar');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-2">
        <?= $this->render('/_profile_menu') ?>
    </div>
    <div class="col-md-10">
        <h2 class="h3 profile-title"><?= Yii::t('yuncms', 'My Avatar') ?></h2>
        <div class="row">
            <div class="col-lg-6">
                <div class="img-container">
                    <?= Html::img(Yii::$app->user->identity->getAvatar('big'), ['id' => 'image', 'alt' => Yii::$app->user->identity->username]); ?>
                </div>
                <?php $form = ActiveForm::begin([
                    'options' => [
                        'enctype' => 'multipart/form-data',
                    ],
                ]); ?>
                <?= $form->field($model, 'x')->hiddenInput(['id' => 'x'])->label(false) ?>
                <?= $form->field($model, 'y')->hiddenInput(['id' => 'y'])->label(false) ?>
                <?= $form->field($model, 'width')->hiddenInput(['id' => 'width'])->label(false) ?>
                <?= $form->field($model, 'height')->hiddenInput(['id' => 'height'])->label(false) ?>
                <div class="docs-buttons">

                    <div class="btn-group">
                        <button type="button" class="btn btn-primary" data-method="zoom" data-option="0.1"
                                title="<?= Yii::t('yuncms', 'Enlarge') ?>" disabled="disabled">
                    <span class="docs-tooltip" data-toggle="tooltip" title=""
                          data-original-title="<?= Yii::t('yuncms', 'Enlarge') ?>">
                        <span class="fa fa-search-plus"></span>
                    </span>
                        </button>
                        <button type="button" class="btn btn-primary" data-method="zoom" data-option="-0.1"
                                title="<?= Yii::t('yuncms', 'Reduce') ?>" disabled="disabled">
                    <span class="docs-tooltip" data-toggle="tooltip" title=""
                          data-original-title="<?= Yii::t('yuncms', 'Reduce') ?>">
                        <span class="fa fa-search-minus"></span>
                    </span>
                        </button>
                    </div>
<!--
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary" data-method="move" data-option="-10"
                                data-second-option="0" title="<?= Yii::t('yuncms', 'Move left') ?>" disabled="disabled">
                    <span class="docs-tooltip" data-toggle="tooltip" title="" data-original-title="<?= Yii::t('yuncms', 'Move left') ?>">
                        <span class="fa fa-arrow-left"></span>
                    </span>
                        </button>
                        <button type="button" class="btn btn-primary" data-method="move" data-option="10"
                                data-second-option="0" title="<?= Yii::t('yuncms', 'Move right') ?>" disabled="disabled">
                    <span class="docs-tooltip" data-toggle="tooltip" title="" data-original-title="<?= Yii::t('yuncms', 'Move right') ?>">
                        <span class="fa fa-arrow-right"></span>
                    </span>
                        </button>
                        <button type="button" class="btn btn-primary" data-method="move" data-option="0"
                                data-second-option="-10" title="<?= Yii::t('yuncms', 'Move up') ?>" disabled="disabled">
                    <span class="docs-tooltip" data-toggle="tooltip" title="" data-original-title="<?= Yii::t('yuncms', 'Move up') ?>">
                        <span class="fa fa-arrow-up"></span>
                    </span>
                        </button>
                        <button type="button" class="btn btn-primary" data-method="move" data-option="0"
                                data-second-option="10" title="<?= Yii::t('yuncms', 'Move Downward') ?>" disabled="disabled">
                    <span class="docs-tooltip" data-toggle="tooltip" title="" data-original-title="<?= Yii::t('yuncms', 'Move Downward') ?>">
                        <span class="fa fa-arrow-down"></span>
                    </span>
                        </button>
                    </div>
-->
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary" data-method="reset"
                                title="<?= Yii::t('yuncms', 'Reset'); ?>">
                            <span class="docs-tooltip" data-toggle="tooltip" title="<?= Yii::t('yuncms', 'Reset'); ?>">
                                <span class="fa fa-refresh"></span>
                            </span>
                            <?= Yii::t('yuncms', 'Refresh') ?>
                        </button>
                        <label class="btn btn-primary btn-upload" for="inputImage"
                               title="<?= Yii::t('yuncms', 'Upload avatar'); ?>">
                            <?= $form->field($model, 'file', [
                                'options' => [
                                    'tag' => false
                                ],
                                'inputOptions' => [
                                    'class' => 'sr-only',
                                    'id' => 'inputImage',
                                    'accept' => 'image/*'
                                ],
                            ])->fileInput()->label(false)->error(false); ?>
                            <span class="docs-tooltip" data-toggle="tooltip"
                                  title="<?= Yii::t('yuncms', 'Upload avatar'); ?>">
                                <span class="fa fa-upload"></span>
                            </span>
                            <?= Yii::t('yuncms', 'Select a File') ?>
                        </label>
                        <?= Html::submitButton('<span class="fa fa-check"></span> ' . Yii::t('yuncms', 'Save'), ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
            <div class="col-lg-6">
                <div class="docs-preview clearfix">
                    <div class="img-preview preview-lg"></div>
                    <div class="img-preview preview-md"></div>
                    <div class="img-preview preview-sm"></div>
                </div>
            </div>
        </div>
    </div>
</div>


