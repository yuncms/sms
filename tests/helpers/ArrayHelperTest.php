<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\tests\helpers;

use ArrayObject;
use stdClass;
use yuncms\base\Collection;
use yuncms\helpers\ArrayHelper;
use yuncms\tests\TestCase;

/**
 * Class ArrayHelperTest
 * @package yuncms\tests\helpers
 * @author Tongle Xu <xutongle@gmail.com>
 */
class ArrayHelperTest extends TestCase
{
    public function testAccessible()
    {
        $this->assertTrue(ArrayHelper::accessible([]));
        $this->assertTrue(ArrayHelper::accessible([1, 2]));
        $this->assertTrue(ArrayHelper::accessible(['a' => 1, 'b' => 2]));
        $this->assertTrue(ArrayHelper::accessible(new Collection));
        $this->assertFalse(ArrayHelper::accessible(null));
        $this->assertFalse(ArrayHelper::accessible('abc'));
        $this->assertFalse(ArrayHelper::accessible(new stdClass));
        $this->assertFalse(ArrayHelper::accessible((object)['a' => 1, 'b' => 2]));
    }

    public function testAdd()
    {
        $array = ArrayHelper::add(['name' => 'Desk'], 'price', 100);
        $this->assertEquals(['name' => 'Desk', 'price' => 100], $array);
    }

    public function testCollapse()
    {
        $data = [['foo', 'bar'], ['baz']];
        $this->assertEquals(['foo', 'bar', 'baz'], ArrayHelper::collapse($data));
    }

    public function testCrossJoin()
    {
        // Single dimension
        $this->assertSame(
            [[1, 'a'], [1, 'b'], [1, 'c']],
            ArrayHelper::crossJoin([1], ['a', 'b', 'c'])
        );
        // Square matrix
        $this->assertSame(
            [[1, 'a'], [1, 'b'], [2, 'a'], [2, 'b']],
            ArrayHelper::crossJoin([1, 2], ['a', 'b'])
        );
        // Rectangular matrix
        $this->assertSame(
            [[1, 'a'], [1, 'b'], [1, 'c'], [2, 'a'], [2, 'b'], [2, 'c']],
            ArrayHelper::crossJoin([1, 2], ['a', 'b', 'c'])
        );
        // 3D matrix
        $this->assertSame(
            [
                [1, 'a', 'I'], [1, 'a', 'II'], [1, 'a', 'III'],
                [1, 'b', 'I'], [1, 'b', 'II'], [1, 'b', 'III'],
                [2, 'a', 'I'], [2, 'a', 'II'], [2, 'a', 'III'],
                [2, 'b', 'I'], [2, 'b', 'II'], [2, 'b', 'III'],
            ],
            ArrayHelper::crossJoin([1, 2], ['a', 'b'], ['I', 'II', 'III'])
        );
        // With 1 empty dimension
        $this->assertEmpty(ArrayHelper::crossJoin([], ['a', 'b'], ['I', 'II', 'III']));
        $this->assertEmpty(ArrayHelper::crossJoin([1, 2], [], ['I', 'II', 'III']));
        $this->assertEmpty(ArrayHelper::crossJoin([1, 2], ['a', 'b'], []));
        // With empty arrays
        $this->assertEmpty(ArrayHelper::crossJoin([], [], []));
        $this->assertEmpty(ArrayHelper::crossJoin([], []));
        $this->assertEmpty(ArrayHelper::crossJoin([]));
        // Not really a proper usage, still, test for preserving BC
        $this->assertSame([[]], ArrayHelper::crossJoin());
    }

    public function testDivide()
    {
        list($keys, $values) = ArrayHelper::divide(['name' => 'Desk']);
        $this->assertEquals(['name'], $keys);
        $this->assertEquals(['Desk'], $values);
    }

    public function testDot()
    {
        $array = ArrayHelper::dot(['foo' => ['bar' => 'baz']]);
        $this->assertEquals(['foo.bar' => 'baz'], $array);
        $array = ArrayHelper::dot([]);
        $this->assertEquals([], $array);
        $array = ArrayHelper::dot(['foo' => []]);
        $this->assertEquals(['foo' => []], $array);
        $array = ArrayHelper::dot(['foo' => ['bar' => []]]);
        $this->assertEquals(['foo.bar' => []], $array);
    }

    public function testExcept()
    {
        $array = ['name' => 'Desk', 'price' => 100];
        $array = ArrayHelper::except($array, ['price']);
        $this->assertEquals(['name' => 'Desk'], $array);
    }

    public function testExists()
    {
        $this->assertTrue(ArrayHelper::exists([1], 0));
        $this->assertTrue(ArrayHelper::exists([null], 0));
        $this->assertTrue(ArrayHelper::exists(['a' => 1], 'a'));
        $this->assertTrue(ArrayHelper::exists(['a' => null], 'a'));
        $this->assertTrue(ArrayHelper::exists(new Collection(['a' => null]), 'a'));
        $this->assertFalse(ArrayHelper::exists([1], 1));
        $this->assertFalse(ArrayHelper::exists([null], 1));
        $this->assertFalse(ArrayHelper::exists(['a' => 1], 0));
        $this->assertFalse(ArrayHelper::exists(new Collection(['a' => null]), 'b'));
    }

    public function testFirst()
    {
        $array = [100, 200, 300];
        $value = ArrayHelper::first($array, function ($value) {
            return $value >= 150;
        });
        $this->assertEquals(200, $value);
        $this->assertEquals(100, ArrayHelper::first($array));
    }

    public function testLast()
    {
        $array = [100, 200, 300];
        $last = ArrayHelper::last($array, function ($value) {
            return $value < 250;
        });
        $this->assertEquals(200, $last);
        $last = ArrayHelper::last($array, function ($value, $key) {
            return $key < 2;
        });
        $this->assertEquals(200, $last);
        $this->assertEquals(300, ArrayHelper::last($array));
    }

    public function testFlatten()
    {
        // Flat arrays are unaffected
        $array = ['#foo', '#bar', '#baz'];
        $this->assertEquals(['#foo', '#bar', '#baz'], ArrayHelper::flatten(['#foo', '#bar', '#baz']));
        // Nested arrays are flattened with existing flat items
        $array = [['#foo', '#bar'], '#baz'];
        $this->assertEquals(['#foo', '#bar', '#baz'], ArrayHelper::flatten($array));
        // Flattened array includes "null" items
        $array = [['#foo', null], '#baz', null];
        $this->assertEquals(['#foo', null, '#baz', null], ArrayHelper::flatten($array));
        // Sets of nested arrays are flattened
        $array = [['#foo', '#bar'], ['#baz']];
        $this->assertEquals(['#foo', '#bar', '#baz'], ArrayHelper::flatten($array));
        // Deeply nested arrays are flattened
        $array = [['#foo', ['#bar']], ['#baz']];
        $this->assertEquals(['#foo', '#bar', '#baz'], ArrayHelper::flatten($array));
        // Nested arrays are flattened alongside arrays
        $array = [new Collection(['#foo', '#bar']), ['#baz']];
        $this->assertEquals(['#foo', '#bar', '#baz'], ArrayHelper::flatten($array));
        // Nested arrays containing plain arrays are flattened
        $array = [new Collection(['#foo', ['#bar']]), ['#baz']];
        $this->assertEquals(['#foo', '#bar', '#baz'], ArrayHelper::flatten($array));
        // Nested arrays containing arrays are flattened
        $array = [['#foo', new Collection(['#bar'])], ['#baz']];
        $this->assertEquals(['#foo', '#bar', '#baz'], ArrayHelper::flatten($array));
        // Nested arrays containing arrays containing arrays are flattened
        $array = [['#foo', new Collection(['#bar', ['#zap']])], ['#baz']];
        $this->assertEquals(['#foo', '#bar', '#zap', '#baz'], ArrayHelper::flatten($array));
    }

    public function testFlattenWithDepth()
    {
        // No depth flattens recursively
        $array = [['#foo', ['#bar', ['#baz']]], '#zap'];
        $this->assertEquals(['#foo', '#bar', '#baz', '#zap'], ArrayHelper::flatten($array));
        // Specifying a depth only flattens to that depth
        $array = [['#foo', ['#bar', ['#baz']]], '#zap'];
        $this->assertEquals(['#foo', ['#bar', ['#baz']], '#zap'], ArrayHelper::flatten($array, 1));
        $array = [['#foo', ['#bar', ['#baz']]], '#zap'];
        $this->assertEquals(['#foo', '#bar', ['#baz'], '#zap'], ArrayHelper::flatten($array, 2));
    }

    public function testGet()
    {
        $array = ['products.desk' => ['price' => 100]];
        $this->assertEquals(['price' => 100], ArrayHelper::get($array, 'products.desk'));
        $array = ['products' => ['desk' => ['price' => 100]]];
        $value = ArrayHelper::get($array, 'products.desk');
        $this->assertEquals(['price' => 100], $value);
        // Test null array values
        $array = ['foo' => null, 'bar' => ['baz' => null]];
        $this->assertNull(ArrayHelper::get($array, 'foo', 'default'));
        $this->assertNull(ArrayHelper::get($array, 'bar.baz', 'default'));
        // Test direct ArrayAccess object
        $array = ['products' => ['desk' => ['price' => 100]]];
        $arrayAccessObject = new ArrayObject($array);
        $value = ArrayHelper::get($arrayAccessObject, 'products.desk');
        $this->assertEquals(['price' => 100], $value);
        // Test array containing ArrayAccess object
        $arrayAccessChild = new ArrayObject(['products' => ['desk' => ['price' => 100]]]);
        $array = ['child' => $arrayAccessChild];
        $value = ArrayHelper::get($array, 'child.products.desk');
        $this->assertEquals(['price' => 100], $value);
        // Test array containing multiple nested ArrayAccess objects
        $arrayAccessChild = new ArrayObject(['products' => ['desk' => ['price' => 100]]]);
        $arrayAccessParent = new ArrayObject(['child' => $arrayAccessChild]);
        $array = ['parent' => $arrayAccessParent];
        $value = ArrayHelper::get($array, 'parent.child.products.desk');
        $this->assertEquals(['price' => 100], $value);
        // Test missing ArrayAccess object field
        $arrayAccessChild = new ArrayObject(['products' => ['desk' => ['price' => 100]]]);
        $arrayAccessParent = new ArrayObject(['child' => $arrayAccessChild]);
        $array = ['parent' => $arrayAccessParent];
        $value = ArrayHelper::get($array, 'parent.child.desk');
        $this->assertNull($value);
        // Test missing ArrayAccess object field
        $arrayAccessObject = new ArrayObject(['products' => ['desk' => null]]);
        $array = ['parent' => $arrayAccessObject];
        $value = ArrayHelper::get($array, 'parent.products.desk.price');
        $this->assertNull($value);
        // Test null ArrayAccess object fields
        $array = new ArrayObject(['foo' => null, 'bar' => new ArrayObject(['baz' => null])]);
        $this->assertNull(ArrayHelper::get($array, 'foo', 'default'));
        $this->assertNull(ArrayHelper::get($array, 'bar.baz', 'default'));
        // Test null key returns the whole array
        $array = ['foo', 'bar'];
        $this->assertEquals($array, ArrayHelper::get($array, null));
        // Test $array not an array
        $this->assertSame('default', ArrayHelper::get(null, 'foo', 'default'));
        $this->assertSame('default', ArrayHelper::get(false, 'foo', 'default'));
        // Test $array not an array and key is null
        $this->assertSame('default', ArrayHelper::get(null, null, 'default'));
        // Test $array is empty and key is null
        $this->assertEmpty(ArrayHelper::get([], null));
        $this->assertEmpty(ArrayHelper::get([], null, 'default'));
    }

    public function testHas()
    {
        $array = ['products.desk' => ['price' => 100]];
        $this->assertTrue(ArrayHelper::has($array, 'products.desk'));
        $array = ['products' => ['desk' => ['price' => 100]]];
        $this->assertTrue(ArrayHelper::has($array, 'products.desk'));
        $this->assertTrue(ArrayHelper::has($array, 'products.desk.price'));
        $this->assertFalse(ArrayHelper::has($array, 'products.foo'));
        $this->assertFalse(ArrayHelper::has($array, 'products.desk.foo'));
        $array = ['foo' => null, 'bar' => ['baz' => null]];
        $this->assertTrue(ArrayHelper::has($array, 'foo'));
        $this->assertTrue(ArrayHelper::has($array, 'bar.baz'));
        $array = new ArrayObject(['foo' => 10, 'bar' => new ArrayObject(['baz' => 10])]);
        $this->assertTrue(ArrayHelper::has($array, 'foo'));
        $this->assertTrue(ArrayHelper::has($array, 'bar'));
        $this->assertTrue(ArrayHelper::has($array, 'bar.baz'));
        $this->assertFalse(ArrayHelper::has($array, 'xxx'));
        $this->assertFalse(ArrayHelper::has($array, 'xxx.yyy'));
        $this->assertFalse(ArrayHelper::has($array, 'foo.xxx'));
        $this->assertFalse(ArrayHelper::has($array, 'bar.xxx'));
        $array = new ArrayObject(['foo' => null, 'bar' => new ArrayObject(['baz' => null])]);
        $this->assertTrue(ArrayHelper::has($array, 'foo'));
        $this->assertTrue(ArrayHelper::has($array, 'bar.baz'));
        $array = ['foo', 'bar'];
        $this->assertFalse(ArrayHelper::has($array, null));
        $this->assertFalse(ArrayHelper::has(null, 'foo'));
        $this->assertFalse(ArrayHelper::has(false, 'foo'));
        $this->assertFalse(ArrayHelper::has(null, null));
        $this->assertFalse(ArrayHelper::has([], null));
        $array = ['products' => ['desk' => ['price' => 100]]];
        $this->assertTrue(ArrayHelper::has($array, ['products.desk']));
        $this->assertTrue(ArrayHelper::has($array, ['products.desk', 'products.desk.price']));
        $this->assertTrue(ArrayHelper::has($array, ['products', 'products']));
        $this->assertFalse(ArrayHelper::has($array, ['foo']));
        $this->assertFalse(ArrayHelper::has($array, []));
        $this->assertFalse(ArrayHelper::has($array, ['products.desk', 'products.price']));
        $this->assertFalse(ArrayHelper::has([], [null]));
        $this->assertFalse(ArrayHelper::has(null, [null]));
    }

    public function testIsAssoc()
    {
        $this->assertTrue(ArrayHelper::isAssoc(['a' => 'a', 0 => 'b']));
        $this->assertTrue(ArrayHelper::isAssoc([1 => 'a', 0 => 'b']));
        $this->assertTrue(ArrayHelper::isAssoc([1 => 'a', 2 => 'b']));
        $this->assertFalse(ArrayHelper::isAssoc([0 => 'a', 1 => 'b']));
        $this->assertFalse(ArrayHelper::isAssoc(['a', 'b']));
    }

    public function testOnly()
    {
        $array = ['name' => 'Desk', 'price' => 100, 'orders' => 10];
        $array = ArrayHelper::only($array, ['name', 'price']);
        $this->assertEquals(['name' => 'Desk', 'price' => 100], $array);
    }

    public function testPrepend()
    {
        $array = ArrayHelper::prepend(['one', 'two', 'three', 'four'], 'zero');
        $this->assertEquals(['zero', 'one', 'two', 'three', 'four'], $array);
        $array = ArrayHelper::prepend(['one' => 1, 'two' => 2], 0, 'zero');
        $this->assertEquals(['zero' => 0, 'one' => 1, 'two' => 2], $array);
    }

    public function testPull()
    {
        $array = ['name' => 'Desk', 'price' => 100];
        $name = ArrayHelper::pull($array, 'name');
        $this->assertEquals('Desk', $name);
        $this->assertEquals(['price' => 100], $array);
        // Only works on first level keys
        $array = ['joe@example.com' => 'Joe', 'jane@localhost' => 'Jane'];
        $name = ArrayHelper::pull($array, 'joe@example.com');
        $this->assertEquals('Joe', $name);
        $this->assertEquals(['jane@localhost' => 'Jane'], $array);
        // Does not work for nested keys
        $array = ['emails' => ['joe@example.com' => 'Joe', 'jane@localhost' => 'Jane']];
        $name = ArrayHelper::pull($array, 'emails.joe@example.com');
        $this->assertNull($name);
        $this->assertEquals(['emails' => ['joe@example.com' => 'Joe', 'jane@localhost' => 'Jane']], $array);
    }

    public function testRandom()
    {
        $random = ArrayHelper::random(['foo', 'bar', 'baz']);
        $this->assertContains($random, ['foo', 'bar', 'baz']);
        $random = ArrayHelper::random(['foo', 'bar', 'baz'], 0);
        $this->assertInternalType('array', $random);
        $this->assertCount(0, $random);
        $random = ArrayHelper::random(['foo', 'bar', 'baz'], 1);
        $this->assertInternalType('array', $random);
        $this->assertCount(1, $random);
        $this->assertContains($random[0], ['foo', 'bar', 'baz']);
        $random = ArrayHelper::random(['foo', 'bar', 'baz'], 2);
        $this->assertInternalType('array', $random);
        $this->assertCount(2, $random);
        $this->assertContains($random[0], ['foo', 'bar', 'baz']);
        $this->assertContains($random[1], ['foo', 'bar', 'baz']);
        $random = ArrayHelper::random(['foo', 'bar', 'baz'], '0');
        $this->assertInternalType('array', $random);
        $this->assertCount(0, $random);
        $random = ArrayHelper::random(['foo', 'bar', 'baz'], '1');
        $this->assertInternalType('array', $random);
        $this->assertCount(1, $random);
        $this->assertContains($random[0], ['foo', 'bar', 'baz']);
        $random = ArrayHelper::random(['foo', 'bar', 'baz'], '2');
        $this->assertInternalType('array', $random);
        $this->assertCount(2, $random);
        $this->assertContains($random[0], ['foo', 'bar', 'baz']);
        $this->assertContains($random[1], ['foo', 'bar', 'baz']);
    }

    public function testRandomOnEmptyArray()
    {
        $random = ArrayHelper::random([], 0);
        $this->assertInternalType('array', $random);
        $this->assertCount(0, $random);
        $random = ArrayHelper::random([], '0');
        $this->assertInternalType('array', $random);
        $this->assertCount(0, $random);
    }

    public function testRandomThrowsAnErrorWhenRequestingMoreItemsThanAreAvailable()
    {
        $exceptions = 0;
        try {
            ArrayHelper::random([]);
        } catch (\InvalidArgumentException $e) {
            $exceptions++;
        }
        try {
            ArrayHelper::random([], 1);
        } catch (\InvalidArgumentException $e) {
            $exceptions++;
        }
        try {
            ArrayHelper::random([], 2);
        } catch (\InvalidArgumentException $e) {
            $exceptions++;
        }
        $this->assertSame(3, $exceptions);
    }

    public function testSet()
    {
        $array = ['products' => ['desk' => ['price' => 100]]];
        ArrayHelper::set($array, 'products.desk.price', 200);
        $this->assertEquals(['products' => ['desk' => ['price' => 200]]], $array);
    }


    public function testWhere()
    {
        $array = [100, '200', 300, '400', 500];
        $array = ArrayHelper::where($array, function ($value, $key) {
            return is_string($value);
        });
        $this->assertEquals([1 => 200, 3 => 400], $array);
    }

    public function testWhereKey()
    {
        $array = ['10' => 1, 'foo' => 3, 20 => 2];
        $array = ArrayHelper::where($array, function ($value, $key) {
            return is_numeric($key);
        });
        $this->assertEquals(['10' => 1, 20 => 2], $array);
    }

    public function testForget()
    {
        $array = ['products' => ['desk' => ['price' => 100]]];
        ArrayHelper::forget($array, null);
        $this->assertEquals(['products' => ['desk' => ['price' => 100]]], $array);
        $array = ['products' => ['desk' => ['price' => 100]]];
        ArrayHelper::forget($array, []);
        $this->assertEquals(['products' => ['desk' => ['price' => 100]]], $array);
        $array = ['products' => ['desk' => ['price' => 100]]];
        ArrayHelper::forget($array, 'products.desk');
        $this->assertEquals(['products' => []], $array);
        $array = ['products' => ['desk' => ['price' => 100]]];
        ArrayHelper::forget($array, 'products.desk.price');
        $this->assertEquals(['products' => ['desk' => []]], $array);
        $array = ['products' => ['desk' => ['price' => 100]]];
        ArrayHelper::forget($array, 'products.final.price');
        $this->assertEquals(['products' => ['desk' => ['price' => 100]]], $array);
        $array = ['shop' => ['cart' => [150 => 0]]];
        ArrayHelper::forget($array, 'shop.final.cart');
        $this->assertEquals(['shop' => ['cart' => [150 => 0]]], $array);
        $array = ['products' => ['desk' => ['price' => ['original' => 50, 'taxes' => 60]]]];
        ArrayHelper::forget($array, 'products.desk.price.taxes');
        $this->assertEquals(['products' => ['desk' => ['price' => ['original' => 50]]]], $array);
        $array = ['products' => ['desk' => ['price' => ['original' => 50, 'taxes' => 60]]]];
        ArrayHelper::forget($array, 'products.desk.final.taxes');
        $this->assertEquals(['products' => ['desk' => ['price' => ['original' => 50, 'taxes' => 60]]]], $array);
        $array = ['products' => ['desk' => ['price' => 50], null => 'something']];
        ArrayHelper::forget($array, ['products.amount.all', 'products.desk.price']);
        $this->assertEquals(['products' => ['desk' => [], null => 'something']], $array);
        // Only works on first level keys
        $array = ['joe@example.com' => 'Joe', 'jane@example.com' => 'Jane'];
        ArrayHelper::forget($array, 'joe@example.com');
        $this->assertEquals(['jane@example.com' => 'Jane'], $array);
        // Does not work for nested keys
        $array = ['emails' => ['joe@example.com' => ['name' => 'Joe'], 'jane@localhost' => ['name' => 'Jane']]];
        ArrayHelper::forget($array, ['emails.joe@example.com', 'emails.jane@localhost']);
        $this->assertEquals(['emails' => ['joe@example.com' => ['name' => 'Joe']]], $array);
    }

    public function testWrap()
    {
        $string = 'a';
        $array = ['a'];
        $object = new stdClass;
        $object->value = 'a';
        $this->assertEquals(['a'], ArrayHelper::wrap($string));
        $this->assertEquals($array, ArrayHelper::wrap($array));
        $this->assertEquals([$object], ArrayHelper::wrap($object));
    }

    public function testFilterByValue()
    {
        $this->assertSame([
            ['firstKey' => 'firstValue']
        ], ArrayHelper::filterByValue([
            ['firstKey' => 'firstValue'],
            ['secondKey' => 'secondValue']
        ], 'firstKey', 'firstValue'));
    }

    public function testFilterEmptyStringsFromArray()
    {
        $this->assertSame([0 => 1, 1 => 2, 4 => null, 5 => 5], ArrayHelper::filterEmptyStringsFromArray([0 => 1, 1 => 2, 3 => '', 4 => null, 5 => 5]));
    }

    public function testFirstKey()
    {
        $this->assertNull(ArrayHelper::firstKey([]));
        $this->assertEquals(0, ArrayHelper::firstKey([1]));
        $this->assertEquals(5, ArrayHelper::firstKey([5 => 'value']));
        $this->assertEquals('firstKey', ArrayHelper::firstKey(['firstKey' => 'firstValue', 'secondKey' => 'secondValue']));
    }

    public function testFirstValue()
    {
        $this->assertNull(ArrayHelper::firstValue([]));
        $this->assertEquals(1, ArrayHelper::firstValue([1]));
        $this->assertEquals('value', ArrayHelper::firstValue([5 => 'value']));
        $this->assertEquals('firstValue', ArrayHelper::firstValue(['firstKey' => 'firstValue', 'secondKey' => 'secondValue']));
    }

    public function testRename()
    {
        $array = ['foo' => 'bar', 'fizz' => 'plop'];
        ArrayHelper::rename($array, 'foo', 'foo2');
        $this->assertSame(['fizz' => 'plop', 'foo2' => 'bar'], $array);

        $array = ['foo' => 'bar', 'fizz' => 'plop'];
        ArrayHelper::rename($array, 'fooX', 'fooY');
        $this->assertSame(['foo' => 'bar', 'fizz' => 'plop', 'fooY' => null], $array);

        $array = ['foo' => 'bar', 'fizz' => 'plop'];
        ArrayHelper::rename($array, 'fooX', 'foo');
        $this->assertSame(['foo' => 'bar', 'fizz' => 'plop'], $array);

        $array = ['foo' => 'bar', 'fizz' => 'plop'];
        ArrayHelper::rename($array, 'fooX', 'fooY', 'test');
        $this->assertSame(['foo' => 'bar', 'fizz' => 'plop', 'fooY' => 'test'], $array);
    }
}
