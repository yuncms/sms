<?php
use yuncms\admin\widgets\Nav;
use yuncms\admin\helpers\MenuHelper;
$menus = MenuHelper::getAssignedMenu(Yii::$app->user->id);
?>
<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <?= Nav::widget([
            'top' => $this->render(
                '_navigation_header.php'
            ),
            'items' => $menus
        ]) ?>
    </div>
</nav>