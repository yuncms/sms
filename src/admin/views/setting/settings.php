<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

use yii\helpers\Html;
use xutl\inspinia\ActiveForm;
use xutl\inspinia\Box;
use xutl\inspinia\Toolbar;
use xutl\inspinia\Alert;

$this->title = Yii::t('admin', 'Site Setting');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <?= Alert::widget() ?>
            <?php Box::begin([
                'header' => Html::encode($this->title),
            ]); ?>
            <?php $form = ActiveForm::begin(['layout' => 'horizontal',]); ?>

            <?= $form->field($model, 'baseUrl') ?>
            <div class="hr-line-dashed"></div>
            <?= $form->field($model, 'name') ?>
            <div class="hr-line-dashed"></div>
            <?= $form->field($model, 'title') ?>
            <div class="hr-line-dashed"></div>
            <?= $form->field($model, 'keywords') ?>
            <div class="hr-line-dashed"></div>
            <?= $form->field($model, 'description') ?>
            <div class="hr-line-dashed"></div>
            <?= $form->field($model, 'icpBeian') ?>
            <div class="hr-line-dashed"></div>
            <?= $form->field($model, 'beian') ?>
            <div class="hr-line-dashed"></div>
            <?= $form->field($model, 'copyright')->textarea() ?>
            <div class="hr-line-dashed"></div>
            <?= $form->field($model, 'close')->inline()->checkbox([], false) ?>
            <div class="hr-line-dashed"></div>
            <?= $form->field($model, 'closeReason')->textarea() ?>
            <div class="hr-line-dashed"></div>
            <?= $form->field($model, 'analysisCode')->textarea() ?>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <div class="col-sm-4 col-sm-offset-2">
                    <?= Html::submitButton(Yii::t('admin', 'Save'), ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
            <?php Box::end(); ?>
        </div>
    </div>
</div>