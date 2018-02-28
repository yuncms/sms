<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */


namespace yuncms\tests\helpers;

use yuncms\helpers\Json;
use yuncms\tests\TestCase;

class JsonTest extends TestCase
{

    public function testDecodeIfJson()
    {
        $json = '{"name":"Unauthorized","code":0,"status":401}';
        $arrayRes = Json::decodeIfJson($json, true);
        $this->assertArrayHasKey('status', $arrayRes);

        $json1 = 'test';
        $arrayRes1 = Json::decodeIfJson($json1, true);
        $this->assertEquals($json1, $arrayRes1);
    }
}
