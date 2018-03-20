<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\db;

use Yii;
use yuncms\jobs\DeleteActiveRecordJob;
use yuncms\jobs\DeleteAllActiveRecordJob;
use yuncms\jobs\updateActiveRecordAllCountersJob;
use yuncms\jobs\UpdateActiveRecordAllJob;
use yuncms\jobs\UpdateActiveRecordAttributesJob;
use yuncms\jobs\UpdateActiveRecordCountersJob;

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
        $model->loadDefaultValues();
        if ($model->save($runValidation)) {
            return $model;
        }
        return null;
    }

    /**
     * 异步更新属性
     * @param array $attributes
     * @return int|void
     */
    public function updateAttributesAsync($attributes)
    {
        Yii::$app->queue->push(new UpdateActiveRecordAttributesJob([
            'modelName' => get_called_class(),
            'condition' => $this->getPrimaryKey(true),
            'attributes' => $attributes,
        ]));
    }

    /**
     * 异步更新计数器
     * @param $counters
     */
    public function updateCountersAsync($counters)
    {
        Yii::$app->queue->push(new UpdateActiveRecordCountersJob([
            'modelName' => get_called_class(),
            'condition' => $this->getPrimaryKey(true),
            'counters' => $counters,
        ]));
    }

    /**
     * 异步删除
     */
    public function deleteAsync()
    {
        Yii::$app->queue->push(new DeleteActiveRecordJob([
            'modelName' => get_called_class(),
            'condition' => $this->getPrimaryKey(true)
        ]));
    }

    /**
     * 异步更新全表
     * @param array $attributes
     * @param string|array $condition
     * @param array $params the parameters (name => value) to be bound to the query.
     */
    public static function updateAllAsync($attributes, $condition = '', $params = [])
    {
        Yii::$app->queue->push(new UpdateActiveRecordAllJob([
            'modelName' => get_called_class(),
            'condition' => $condition,
            'attributes' => $attributes,
            'params' => $params
        ]));
    }

    /**
     * 异步更新计数器
     * @param array $counters
     * @param string $condition
     * @param array $params the parameters (name => value) to be bound to the query.
     */
    public static function updateAllCountersAsync($counters, $condition = '', $params = [])
    {
        Yii::$app->queue->push(new updateActiveRecordAllCountersJob([
            'modelName' => get_called_class(),
            'condition' => $condition,
            'counters' => $counters,
            'params' => $params
        ]));
    }

    /**
     * 异步删除
     * @param string|array $condition
     * @param array $params
     */
    public static function deleteAllAsync($condition = null, $params = [])
    {
        Yii::$app->queue->push(new DeleteAllActiveRecordJob([
            'modelName' => get_called_class(),
            'condition' => $condition,
            'params' => $params
        ]));
    }
}