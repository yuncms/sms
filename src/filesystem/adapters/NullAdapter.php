<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\filesystem\adapters;

use Yii;
use League\Flysystem\AdapterInterface;
use yuncms\filesystem\FilesystemAdapter;

/**
 * Class NullAdapter
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class NullAdapter extends FilesystemAdapter
{

    /**
     * @return AdapterInterface
     */
    protected function createDriver()
    {
        return new \League\Flysystem\Adapter\NullAdapter();
    }
}
