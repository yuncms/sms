<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\console\controllers;

use Yii;
use yuncms\helpers\ArrayHelper;

/**
 * Class MigrateController
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class MigrateController extends \yii\console\controllers\MigrateController
{
    /**
     * @var string|array the directory containing the migration classes. This can be either
     * a [path alias](guide:concept-aliases) or a directory path.
     *
     * Migration classes located at this path should be declared without a namespace.
     * Use [[migrationNamespaces]] property in case you are using namespaced migrations.
     *
     * If you have set up [[migrationNamespaces]], you may set this field to `null` in order
     * to disable usage of migrations that are not namespaced.
     *
     * Since version 2.0.12 you may also specify an array of migration paths that should be searched for
     * migrations to load. This is mainly useful to support old extensions that provide migrations
     * without namespace and to adopt the new feature of namespaced migrations while keeping existing migrations.
     *
     * In general, to load migrations from different locations, [[migrationNamespaces]] is the preferable solution
     * as the migration name contains the origin of the migration in the history, which is not the case when
     * using multiple migration paths.
     *
     * @see $migrationNamespaces
     */
    public $migrationPath = [
        '@app/migrations',
        //'@yii/caching/migrations',
        //'@yii/log/migrations',
        '@yii/web/migrations',
        //'@yii/rbac/migrations',
        '@yii/i18n/migrations',
        '@vendor/yuncms/framework/migrations',
        '@yuncms/admin/migrations',
        '@yuncms/user/migrations',
        '@yuncms/oauth2/migrations',
    ];

    /**
     * 初始化
     */
    public function init()
    {
        parent::init();
        $this->initMigrationPath();
    }

    /**
     * 初始化迁移路径
     */
    public function initMigrationPath()
    {
        $manifestFile = Yii::getAlias('@vendor/yuncms/migrations.php');
        if (is_file($manifestFile)) {
            $migrationPaths = require($manifestFile);
            $this->migrationPath = ArrayHelper::merge($this->migrationPath, $migrationPaths);
        }
        $this->migrationPath = array_values($this->migrationPath);
    }
}