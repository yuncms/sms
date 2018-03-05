<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */


namespace yuncms\tests\behaviors;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Connection;
use yii\db\Exception;
use yuncms\behaviors\UUIDBehavior;
use yuncms\tests\TestCase;
use yuncms\helpers\StringHelper;

/**
 * Class UUIDBehaviorTest
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class UUIDBehaviorTest extends TestCase
{
    /**
     * @var Connection test db connection
     */
    protected $dbConnection;

    public static function setUpBeforeClass()
    {
        if (!extension_loaded('pdo') || !extension_loaded('pdo_sqlite')) {
            static::markTestSkipped('PDO and SQLite extensions are required.');
        }
    }

    public function setUp()
    {
        $this->mockApplication([
            'components' => [
                'db' => [
                    'class' => '\yii\db\Connection',
                    'dsn' => 'sqlite::memory:',
                ],
            ],
        ]);



        $columns = [
            'id' => 'pk',
            'name' => 'string',
            'uuid' => 'string',
            'category_id' => 'integer',
        ];
        try {
            Yii::$app->getDb()->createCommand()->createTable('test_uuid', $columns)->execute();
        } catch (Exception $e) {
        }
    }

    public function tearDown()
    {
        Yii::$app->getDb()->close();
        parent::tearDown();
        gc_enable();
        gc_collect_cycles();
    }

    // Tests :

    public function testUUID()
    {
        $model = new ActiveRecordUUID();
        $model->name = 'test name';
        $model->validate();
        $this->assertTrue(StringHelper::isUUID($model->uuid));
    }

    /**
     * @depends testUUID
     */
    public function testUniqueByIncrement()
    {
        $name = 'test name';
        $model = new ActiveRecordUUIDUnique();
        $model->name = $name;
        $model->save();

        $model1 = new ActiveRecordUUIDUnique();
        $model1->name = $name;
        $model1->save();
        $this->assertNotEquals($model->uuid, $model1->uuid);
    }

    /**
     * @depends testUniqueByIncrement
     */
    public function testUniqueByCallback()
    {
        $name = 'test name';
        $model = new ActiveRecordUUIDUnique();
        $model->name = $name;
        $model->getUUIDBehavior()->value = function () {return StringHelper::UUID();};
        $model->save();
        $this->assertTrue(StringHelper::isUUID($model->uuid));
    }

    /**
     * @depends testUUID
     */
    public function testUpdateUnique()
    {
        $name = 'test name';

        $model = new ActiveRecordUUIDUnique();
        $model->name = $name;
        $model->save();
        $uuid = $model->uuid;

        $model->save();
        $this->assertEquals($uuid, $model->uuid);

        $model = ActiveRecordUUIDUnique::find()->one();
        $model->save();
        $this->assertEquals($uuid, $model->uuid);

        $model->name = 'test-name';
        $model->save();
        $this->assertEquals($uuid, $model->uuid);
    }
}

/**
 * Test Active Record class with [[UUIDBehavior]] behavior attached.
 *
 * @property int $id
 * @property string $name
 * @property string $uuid
 *
 * @property UUIDBehavior $UUIDBehavior
 */
class ActiveRecordUUID extends ActiveRecord
{
    public function behaviors()
    {
        return [
            'uuid' => [
                'class' => UUIDBehavior::class,
                'attribute' => 'uuid',
            ],
        ];
    }

    public static function tableName()
    {
        return 'test_uuid';
    }

    /**
     * @return \yii\base\Behavior|UUIDBehavior
     */
    public function getUUIDBehavior()
    {
        return $this->getBehavior('uuid');
    }
}

class ActiveRecordUUIDUnique extends ActiveRecordUUID
{
    public function behaviors()
    {
        return [
            'uuid' => [
                'class' => UUIDBehavior::class,
                'attribute' => 'uuid',
                'ensureUnique' => true,
            ],
        ];
    }
}
