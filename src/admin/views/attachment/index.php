<?php
use yii\web\View;
use yii\widgets\Pjax;
use yuncms\grid\GridView;
use yuncms\helpers\Html;
use yuncms\admin\widgets\Box;
use yuncms\admin\widgets\Toolbar;
use yuncms\admin\widgets\Alert;

/* @var $this yii\web\View */
/* @var $searchModel yuncms\admin\models\AttachmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('yuncms', 'Manage Attachment');
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs("jQuery(\"#batch_deletion\").on(\"click\", function () {
    yii.confirm('" . Yii::t('app', 'Are you sure you want to delete this item?') . "',function(){
        var ids = jQuery('#gridview').yiiGridView(\"getSelectedRows\");
        jQuery.post(\"/admin/attachment/batch-delete\",{ids:ids});
    });
});", View::POS_LOAD);
?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <?= Alert::widget() ?>
            <?php Pjax::begin(); ?>
            <?php Box::begin([
                'header' => Html::encode($this->title),
            ]); ?>
            <div class="row">
                <div class="col-sm-4 m-b-xs">
                    <?= Toolbar::widget(['items' =>
                        [
                            [
                                'label' => Yii::t('yuncms', 'Manage Attachment'),
                                'url' => ['index'],
                            ],
                            [
                                'options' => ['id' => 'batch_deletion', 'class' => 'btn btn-sm btn-danger'],
                                'label' => Yii::t('yuncms', 'Batch Deletion'),
                                'url' => 'javascript:void(0);',
                            ]
                        ]
                    ]); ?>
                </div>
                <div class="col-sm-8 m-b-xs">
                    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
                </div>
            </div>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'options' => ['id' => 'gridview'],
                'layout' => "{items}\n{summary}\n{pager}",
                //'filterModel' => $searchModel,
                'columns' => [
                    [
                        'class' => 'yii\grid\CheckboxColumn',
                        "name" => "id",
                    ],
                    'id',
                    'user.nickname',
                    'filename',
                    'original_name',
                    'size',
                    'type',
                    'ip',
                    'created_at:datetime',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => Yii::t('yuncms', 'Operation'),
                        'template' => '{view} {delete}',
                    ],
                ],
            ]); ?>
            <?php Box::end(); ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>
