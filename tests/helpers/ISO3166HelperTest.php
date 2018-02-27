<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\tests\helpers;

use yuncms\core\helpers\ISO3166Helper;
use yuncms\tests\TestCase;

/**
 * ISO3166HelperTest
 */
class ISO3166HelperTest extends TestCase
{

    public function testPhoneCode()
    {
        $this->assertEquals('86', ISO3166Helper::phoneCode('CN'));
        $this->assertEquals('81', ISO3166Helper::phoneCode('JP'));
    }

    public function testIsValid()
    {
        $this->assertTrue(ISO3166Helper::isValid('CN'));
        $this->assertFalse(ISO3166Helper::isValid('CNY'));
    }

    public function testCountry()
    {
        \Yii::$app->language = 'en-US';
        $this->assertEquals('China', ISO3166Helper::country('CN', false));

        $this->assertEquals('China', ISO3166Helper::country('CN'));
        \Yii::$app->language = 'zh-CN';
        $this->assertEquals('China', ISO3166Helper::country('CN', false));

        $this->assertEquals('中国', ISO3166Helper::country('CN'));
    }
}