<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\sphinx;

use Yii;
use yuncms\helpers\Json;
use yuncms\sphinx\jobs\CreateActiveRecordJob;
use yuncms\sphinx\jobs\DeleteActiveRecordJob;
use yuncms\sphinx\jobs\DeleteAllActiveRecordJob;
use yuncms\sphinx\jobs\updateActiveRecordAllCountersJob;
use yuncms\sphinx\jobs\UpdateActiveRecordAllJob;
use yuncms\sphinx\jobs\UpdateActiveRecordAttributesJob;
use yuncms\sphinx\jobs\UpdateActiveRecordCountersJob;

/**
 * Class ActiveRecord
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class ActiveRecord extends \yii\sphinx\ActiveRecord
{
    /**
     * Converts the model into an json.
     *
     * This method will first identify which fields to be included in the resulting array by calling [[resolveFields()]].
     * If the model implements the [[Linkable]] interface, the resulting json will also have a `_link` element
     * which refers to a list of links as specified by the interface.
     *
     * @param array $fields the fields being requested.
     * If empty or if it contains '*', all fields as specified by [[fields()]] will be returned.
     * Fields can be nested, separated with dots (.). e.g.: item.field.sub-field
     * `$recursive` must be true for nested fields to be extracted. If `$recursive` is false, only the root fields will be extracted.
     * @param array $expand the additional fields being requested for exporting. Only fields declared in [[extraFields()]]
     * will be considered.
     * Expand can also be nested, separated with dots (.). e.g.: item.expand1.expand2
     * `$recursive` must be true for nested expands to be extracted. If `$recursive` is false, only the root expands will be extracted.
     * @param bool $recursive whether to recursively return array representation of embedded objects.
     * @return string the json representation of the object
     */
    public function toJson(array $fields = [], array $expand = [], $recursive = true)
    {
        return Json::encode($this->toArray($fields, $expand, $recursive));
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
     * 快速创建实例
     * @param array $attributes
     * @param boolean $runValidation
     * @return null|ActiveRecord
     */
    public static function create(array $attributes, $runValidation = true)
    {
        $model = new static();
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
     */
    public static function updateAllCountersAsync($counters, $condition = '')
    {
        Yii::$app->queue->push(new UpdateActiveRecordAllCountersJob([
            'modelClass' => get_called_class(),
            'condition' => $condition,
            'counters' => $counters,
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