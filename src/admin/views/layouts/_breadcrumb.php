<?php
use yuncms\helpers\Html;
use yii\widgets\Breadcrumbs;
?>

<?php if (isset($this->params['breadcrumbs']) && $this->params['breadcrumbs'] != false): ?>
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <?php
            $breadcrumbTitle = $this->params['breadcrumbs'][count($this->params['breadcrumbs']) - 1];
            if (is_array($breadcrumbTitle)) {
                $breadcrumbTitle = $breadcrumbTitle['label'];
            } ?>
            <h2><?= Html::encode($breadcrumbTitle) ?></h2>
            <!-- breadcrumb -->
            <?= Breadcrumbs::widget([
                'tag' => 'ol',
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <!-- end breadcrumb -->

        </div>
        <div class="col-lg-2">
        </div>
    </div>
<?php endif; ?>
