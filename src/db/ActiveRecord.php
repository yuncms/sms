<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\db;

use Yii;
use yuncms\db\jobs\CreateActiveRecordJob;
use yuncms\db\jobs\DeleteActiveRecordJob;
use yuncms\db\jobs\DeleteAllActiveRecordJob;
use yuncms\db\jobs\updateActiveRecordAllCountersJob;
use yuncms\db\jobs\UpdateActiveRecordAllJob;
use yuncms\db\jobs\UpdateActiveRecordAttributesJob;
use yuncms\db\jobs\UpdateActiveRecordCountersJob;

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
        $model = new static();
        $model->loadDefaultValues();
        $model->load($attributes, '');
        if ($model->save($runValidation)) {
            return $model;
        }
        return null;
    }

    /**
     * 快速创建实例
     * @param array $attributes
     * @param boolean $runValidation
     * @return bool|ActiveRecord
     */
    public static function createAsync(array $attributes, $runValidation = true)
    {
        if ($runValidation) {
            $model = new static();
            $model->loadDefaultValues();
            $model->load($attributes, '');
            if (!$model->validate()) {
                return $model;
            }
        }
        Yii::$app->queue->push(new CreateActiveRecordJob([
            'modelClass' => get_called_class(),
            'attributes' => $attributes,
            'runValidation' => $runValidation
        ]));
        return true;
    }

    /**
     * 异步更新属性
     * @param array $attributes
     * @return int|void
     */
    public function updateAttributesAsync($attributes)
    {
        Yii::$app->queue->push(new UpdateActiveRecordAttributesJob([
            'modelClass' => get_called_class(),
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
            'modelClass' => get_called_class(),
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
            'modelClass' => get_called_class(),
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
            'modelClass' => get_called_class(),
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
            'modelClass' => get_called_class(),
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
            'modelClass' => get_called_class(),
            'condition' => $condition,
            'params' => $params
        ]));
    }
}