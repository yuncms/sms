<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\tests\helpers;

use yuncms\helpers\FileHelper;
use yuncms\tests\TestCase;

/**
 * FileHelperTest
 */
class FileHelperTest extends TestCase
{

    public function testHasExtension()
    {
        $this->assertTrue(FileHelper::hasExtension('filename.txt'));
        $this->assertFalse(FileHelper::hasExtension('filename'));
    }

    public function testIsAbsolutePath()
    {
        $this->assertTrue(FileHelper::isAbsolutePath('/tmp'));
        $this->assertTrue(FileHelper::isAbsolutePath('c:\\Windows'));
        $this->assertFalse(FileHelper::isAbsolutePath('tmp'));
        $this->assertFalse(FileHelper::isAbsolutePath('../dir'));
    }

}