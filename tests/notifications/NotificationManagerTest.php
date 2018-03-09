<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\tests\notifications;

use yii\base\BaseObject;
use yuncms\notifications\NotificationManager;
use yuncms\tests\TestCase;

class NotificationManagerTest extends TestCase
{
    public function testCallable()
    {
        // anonymous function
        $container = new NotificationManager();
        $className = TestClass::class;
        $container->set($className, function () {
            return new TestClass([
                'prop1' => 100,
                'prop2' => 200,
            ]);
        });
        $object = $container->get($className);
        $this->assertInstanceOf($className, $object);
        $this->assertEquals(100, $object->prop1);
        $this->assertEquals(200, $object->prop2);
        // static method
        $container = new NotificationManager();
        $className = TestClass::class;
        $container->set($className, [__NAMESPACE__ . '\\Creator', 'create']);
        $object = $container->get($className);
        $this->assertInstanceOf($className, $object);
        $this->assertEquals(1, $object->prop1);
        $this->assertNull($object->prop2);
    }
    public function testObject()
    {
        $object = new TestClass();
        $className = TestClass::class;
        $container = new NotificationManager();
        $container->set($className, $object);
        $this->assertSame($container->get($className), $object);
    }
    public function testShared()
    {
        // with configuration: shared
        $container = new NotificationManager();
        $className = TestClass::class;
        $container->set($className, [
            'class' => $className,
            'prop1' => 10,
            'prop2' => 20,
        ]);
        $object = $container->get($className);
        $this->assertEquals(10, $object->prop1);
        $this->assertEquals(20, $object->prop2);
        $this->assertInstanceOf($className, $object);
        // check shared
        $object2 = $container->get($className);
        $this->assertInstanceOf($className, $object2);
        $this->assertSame($object, $object2);
    }
    /**
     * @see https://github.com/yiisoft/yii2/issues/11771
     */
    public function testModulePropertyIsset()
    {
        $config = [
            'channels' => [
                'captcha' => [
                    'name' => 'foo bar',
                    'class' => 'yii\captcha\Captcha',
                ],
            ],
        ];
        $app = new NotificationManager($config);
        $this->assertTrue(isset($app->captcha->name));
        $this->assertNotEmpty($app->captcha->name);
        $this->assertEquals('foo bar', $app->captcha->name);
        $this->assertTrue(isset($app->captcha->name));
        $this->assertNotEmpty($app->captcha->name);
    }
}

class Creator
{
    public static function create()
    {
        return new TestClass();
    }
}
class TestClass extends BaseObject
{
    public $prop1 = 1;
    public $prop2;
}