<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\tests\base;

use ReflectionClass;
use stdClass;
use yuncms\base\Collection;
use yuncms\tests\TestCase;

/**
 * Class CollectionTest
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class CollectionTest extends TestCase
{
    public function testFirstReturnsFirstItemInCollection()
    {
        $c = new Collection(['foo', 'bar']);
        $this->assertEquals('foo', $c->first());
    }

    public function testLastReturnsLastItemInCollection()
    {
        $c = new Collection(['foo', 'bar']);
        $this->assertEquals('bar', $c->last());
    }

    public function testCollectionIsConstructed()
    {
        $collection = new Collection;
        $this->assertEmpty($collection->all());
    }

    public function testOffsetAccess()
    {
        $c = new Collection(['name' => 'taylor']);
        $this->assertEquals('taylor', $c['name']);
        $c['name'] = 'dayle';
        $this->assertEquals('dayle', $c['name']);
        $this->assertTrue(isset($c['name']));
        unset($c['name']);
        $this->assertFalse(isset($c['name']));
        $c[] = 'jason';
        $this->assertEquals('jason', $c[0]);
    }

    public function testArrayAccessOffsetExists()
    {
        $c = new Collection(['foo', 'bar']);
        $this->assertTrue($c->offsetExists(0));
        $this->assertTrue($c->offsetExists(1));
        $this->assertFalse($c->offsetExists(1000));
    }

    public function testArrayAccessOffsetGet()
    {
        $c = new Collection(['foo', 'bar']);
        $this->assertEquals('foo', $c->offsetGet(0));
        $this->assertEquals('bar', $c->offsetGet(1));
    }
    public function testArrayAccessOffsetSet()
    {
        $c = new Collection(['foo', 'foo']);
        $c->offsetSet(1, 'bar');
        $this->assertEquals('bar', $c[1]);
        $c->offsetSet(null, 'qux');
        $this->assertEquals('qux', $c[2]);
    }

    public function testArrayAccessOffsetUnset()
    {
        $c = new Collection(['foo', 'bar']);
        $c->offsetUnset(1);
        $this->assertFalse(isset($c[1]));
    }


    public function testCountable()
    {
        $c = new Collection(['foo', 'bar']);
        $this->assertCount(2, $c);
    }

    public function testIterable()
    {
        $c = new Collection(['foo']);
        $this->assertInstanceOf('ArrayIterator', $c->getIterator());
        $this->assertEquals(['foo'], $c->getIterator()->getArrayCopy());
    }

    public function testHas()
    {
        $data = new Collection(['id' => 1, 'first' => 'Hello', 'second' => 'World']);
        $this->assertTrue($data->has('first'));
        $this->assertFalse($data->has('third'));
    }

    public function testMakeMethod()
    {
        $collection = Collection::make('foo');
        $this->assertEquals(['foo'], $collection->all());
    }

    public function testMakeMethodFromNull()
    {
        $collection = Collection::make();
        $this->assertEquals([], $collection->all());
    }

    public function testMakeMethodFromArray()
    {
        $collection = Collection::make(['foo' => 'bar']);
        $this->assertEquals(['foo' => 'bar'], $collection->all());
    }

    public function testWrapWithScalar()
    {
        $collection = Collection::wrap('foo');
        $this->assertEquals(['foo'], $collection->all());
    }

    public function testConstructMakeFromObject()
    {
        $object = new stdClass;
        $object->foo = 'bar';
        $collection = Collection::make($object);
        $this->assertEquals(['foo' => 'bar'], $collection->all());
    }
    public function testConstructMethod()
    {
        $collection = new Collection('foo');
        $this->assertEquals(['foo'], $collection->all());
    }
    public function testConstructMethodFromNull()
    {
        $collection = new Collection(null);
        $this->assertEquals([], $collection->all());
        $collection = new Collection;
        $this->assertEquals([], $collection->all());
    }
    public function testConstructMethodFromCollection()
    {
        $firstCollection = new Collection(['foo' => 'bar']);
        $secondCollection = new Collection($firstCollection);
        $this->assertEquals(['foo' => 'bar'], $secondCollection->all());
    }
    public function testConstructMethodFromArray()
    {
        $collection = new Collection(['foo' => 'bar']);
        $this->assertEquals(['foo' => 'bar'], $collection->all());
    }
    public function testConstructMethodFromObject()
    {
        $object = new stdClass;
        $object->foo = 'bar';
        $collection = new Collection($object);
        $this->assertEquals(['foo' => 'bar'], $collection->all());
    }

    
}
