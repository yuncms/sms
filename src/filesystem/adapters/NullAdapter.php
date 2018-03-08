<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */


namespace yuncms\filesystem\adapters;

use Yii;
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
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Yii::t('yuncms', 'Null');
    }

    /**
     * @return \League\Flysystem\Adapter\NullAdapter
     */
    protected function prepareAdapter()
    {
        return new \League\Flysystem\Adapter\NullAdapter();
    }
}