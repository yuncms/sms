<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */


namespace yuncms\tests\helpers;

use yuncms\helpers\Html;
use yuncms\tests\TestCase;

class HtmlTest extends TestCase
{

    public function testEncodeParams()
    {
        $html = '<html lang="zh-CN">';
        $this->assertEquals($html, Html::encodeParams($html));
    }
}
