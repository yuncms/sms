<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */


namespace yuncms\filesystem\adapters;

use yuncms\filesystem\Adapter;

/**
 * Class NullAdapter
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class NullAdapter extends Adapter
{
    /**
     * @return \League\Flysystem\Adapter\NullAdapter
     */
    protected function prepareAdapter()
    {
        return new \League\Flysystem\Adapter\NullAdapter();
    }
}