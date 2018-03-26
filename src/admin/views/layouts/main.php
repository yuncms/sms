<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Url;
use yii\helpers\Html;
use yuncms\assets\InspiniaAsset;

$assetBundle = InspiniaAsset::register($this);

$this->title = 'Manage Center';
print_r(Yii::$app->user->identity);
exit;
?>
<?php $this->beginPage() ?><!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?= Html::tag('title', Html::encode($this->title)); ?>
        <?= Html::csrfMetaTags() ?>
        <?php $this->head() ?>
    </head>
    <body>
    <?php $this->beginBody() ?>
    <!-- Wrapper-->
    <div id="wrapper">

        <!-- Navigation -->
        <?= $this->render('_navigation.php', ['assetBundle' => $assetBundle]) ?>

        <!-- Page wraper -->
        <div id="page-wrapper" class="gray-bg">

            <!-- Page wrapper -->
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
                    <div class="navbar-header">
                        <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i
                                    class="fa fa-bars"></i> </a>
                    </div>


                    <ul class="nav navbar-top-links navbar-right">
                        <li>
                            <span class="m-r-sm text-muted welcome-message">Welcome <?=Yii::$app->user->identity->username?>.</span>
                        </li>
                        <li>
                            <?= Html::a('<i class="fa fa-sign-out"></i>' . Yii::t('admin', 'Logout'), Url::to(['/admin/security/logout']), [
                                'title' => Yii::t('admin', 'Sign Out'),
                                'data' => [
                                    'method' => 'post',
                                    'confirm' => Yii::t('admin', 'You can improve your security further after logging out by closing this opened browser')
                                ]
                            ]); ?>
                        </li>
                    </ul>
                </nav>
            </div>

            <!-- Main view  -->
            <?= $this->render('_breadcrumb.php', ['assetBundle' => $assetBundle]) ?>

            <!-- Content view  -->
            <?= $content ?>
            <!-- Footer -->
            <?= $this->render('_footer.php', ['assetBundle' => $assetBundle]) ?>

        </div>
        <!-- End page wrapper-->

    </div>
    <!-- End wrapper-->

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>