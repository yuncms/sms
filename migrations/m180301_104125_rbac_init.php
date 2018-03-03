<?php

use yii\base\InvalidConfigException;
use yii\db\Migration;
use yii\rbac\DbManager;
use yuncms\rbac\rules\RouteRule;
use yuncms\rbac\rules\GuestRule;

class m180301_104125_rbac_init extends Migration
{
    /**
     * @throws yii\base\InvalidConfigException
     * @return DbManager
     */
    protected function getAuthManager()
    {
        $authManager = Yii::$app->getAuthManager();
        if (!$authManager instanceof DbManager) {
            throw new InvalidConfigException('You should configure "authManager" component to use database before executing this migration.');
        }

        return $authManager;
    }

    /*
    public function safeUp()
    {

    }

    public function safeDown()
    {

    }*/

    // Use up()/down() to run migration code without a transaction.

    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $authManager = $this->getAuthManager();

        $routeRule = new RouteRule();
        $authManager->add($routeRule);

        $guestRule = new GuestRule();
        $authManager->add($guestRule);

        $superAdmin = $authManager->createRole('SuperAdministrator');
        $superAdmin->description = '超级管理员对系统有不受限制的完全访问权。';
        $superAdmin->ruleName = $routeRule->name;
        $authManager->add($superAdmin);

        $admin = $authManager->createRole('Administrator');
        $admin->description = '防止管理员进行有意或无意的系统范围的更改，但是可以执行大部分管理操作。';
        $admin->ruleName = $routeRule->name;
        $authManager->add($admin);

        $defaultPermission = $authManager->createPermission('/*');
        $authManager->add($defaultPermission);

        $authManager->addChild($superAdmin, $defaultPermission);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $authManager = $this->getAuthManager();
        $authManager->removeAll();
    }

}
