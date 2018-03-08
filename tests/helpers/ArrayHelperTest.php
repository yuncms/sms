<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */


namespace yuncms\tests\helpers;

use yuncms\helpers\ArrayHelper;
use yuncms\tests\TestCase;

class ArrayHelperTest extends TestCase
{

    public function testPrependOrAppend()
    {
        $array = [1, 2, 3];
        ArrayHelper::prependOrAppend($array, 4, false);
        $this->assertSame([1, 2, 3, 4], $array);

        $array = [1, 2, 3];
        ArrayHelper::prependOrAppend($array, 4, true);
        $this->assertSame([4, 1, 2, 3], $array);
    }

    public function testFilterEmptyStringsFromArray()
    {
        $this->assertSame([0 => 1, 1 => 2, 4 => null, 5 => 5], ArrayHelper::filterEmptyStringsFromArray([0 => 1, 1 => 2, 3 => '', 4 => null, 5=> 5]));
    }

    public function testFirstKey()
    {
        $this->assertNull(ArrayHelper::firstKey([]));
        $this->assertEquals(0, ArrayHelper::firstKey([1]));
        $this->assertEquals(5, ArrayHelper::firstKey([5 => 'value']));
        $this->assertEquals('firstKey', ArrayHelper::firstKey(['firstKey' => 'firstValue', 'secondKey' => 'secondValue']));
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
