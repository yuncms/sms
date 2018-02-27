<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\tests\validators;

use yuncms\tests\TestCase;
use yuncms\validators\QqValidator;

class QqValidatorTest extends TestCase
{


    public function testValidateValue()
    {
        $val = new QqValidator;
        $this->assertTrue($val->validate('12345'));
        $this->assertTrue($val->validate('123456'));
        $this->assertTrue($val->validate('1234567'));
        $this->assertTrue($val->validate('12345678'));
        $this->assertTrue($val->validate('123456789'));
        $this->assertTrue($val->validate('1234567890'));
        $this->assertTrue($val->validate('12345678901'));

        $this->assertFalse($val->validate('1234'));
        $this->assertFalse($val->validate('151666612345'));
        $this->assertFalse($val->validate(null));
        $this->assertFalse($val->validate([]));

    }
}