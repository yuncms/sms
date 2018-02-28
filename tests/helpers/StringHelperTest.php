<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */


namespace yuncms\tests\helpers;

use PHPUnit\Framework\TestCase;
use yuncms\helpers\StringHelper;

class StringHelperTest extends TestCase
{
    public function testBetweenStr(){
        $text = '123bbb321';
        $str = StringHelper::betweenStr($text,'123','321');
        $this->assertEquals('bbb',$str);
    }

    public function testUUID()
    {
        $uuid = StringHelper::UUID();
        $this->assertTrue(StringHelper::isUUID($uuid));
    }

    public function testIsUUID()
    {
        $uuid = 'd71e0bb9-c6e9-482b-a020-5bf67f0efbaf';
        $this->assertTrue(StringHelper::isUUID($uuid));

        $uuid1 = '132456798';
        $this->assertFalse(StringHelper::isUUID($uuid1));
    }
}
