<?php
use yii\helpers\Url;
use yii\helpers\Html;
 ?>
<li class="nav-header">
    <div class="logo-element-main">
        YUNCMS
    </div>
<!--    <div class="dropdown profile-element">-->
<!--        <span><i class="fa fa-user-circle-o fa-5x" aria-hidden="true"></i></span>-->
<!--        <a data-toggle="dropdown" class="dropdown-toggle" href="#">-->
<!--                        <span class="clear">-->
<!--                            <span class="block m-t-xs">-->
<!--                                <strong class="font-bold"> --><?//=Yii::$app->user->identity->username?><!-- </strong>-->
<!--                            </span>-->
<!--                            <span class="text-muted text-xs block">--><?//=Yii::t('admin', 'Operating')?><!-- <b class="caret"></b></span>-->
<!--                        </span>-->
<!--        </a>-->
<!--        <ul class="dropdown-menu animated fadeInRight m-t-xs">-->
<!--            <li>--><?//= Html::a(Yii::t('admin', 'Logout'), Url::to(['/admin/security/logout']), [
//                'title' => Yii::t('admin', 'Sign Out'),
//                'data' => [
//                    'method' => 'post',
//                    'confirm' => Yii::t('admin', 'You can improve your security further after logging out by closing this opened browser')
//                ]
//            ]); ?><!--</li>-->
<!--        </ul>-->
<!--    </div>-->
    <div class="logo-element">
        YUN
    </div>
</li>
