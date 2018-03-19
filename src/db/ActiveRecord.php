<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\db;

use Yii;
use yii\caching\ChainedDependency;
use yii\caching\DbDependency;
use yii\db\Connection;

/**
 * Class ActiveRecord
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class ActiveRecord extends \yii\db\ActiveRecord
{
    /**
     * 快速创建实例
     * @param array $attributes
     * @param boolean $runValidation
     * @return null|ActiveRecord
     */
    public static function create(array $attributes, $runValidation = true)
    {
        $model = new static ($attributes);
        if ($model->save($runValidation)) {
            return $model;
        }
        return null;
    }


}