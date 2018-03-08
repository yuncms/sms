<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\tests\base;

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
}
