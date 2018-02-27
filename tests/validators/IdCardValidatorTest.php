<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\tests\validators;

use yuncms\tests\TestCase;
use yuncms\validators\IdCardValidator;

/**
 * Class IdCardValidatorTest
 * @package tests\validators
 */
class IdCardValidatorTest extends TestCase
{

    public function testPastDateValidator()
    {
        $validator = new IdCardValidator();
        $this->assertFalse($validator->validate('370'));
        $this->assertTrue($validator->validate('110102199901016072'));
        $this->assertTrue($validator->validate('110226199901014989'));
        $this->assertFalse($validator->validate('110226199901014988'));
    }
}