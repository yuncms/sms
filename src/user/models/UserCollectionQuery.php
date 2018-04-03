<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\models;

use yii\db\ActiveQuery;

/**
 * Class UserCollectionQuery
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class UserCollectionQuery extends ActiveQuery
{

    /**
     * @var string 模型类型
     */
    public $model_class;

    /**
     * @var string 数据表名称
     */
    public $tableName;

    /**
     * @param \yii\db\QueryBuilder $builder
     * @return $this|\yii\db\Query
     */
    public function prepare($builder)
    {
        if (!empty($this->model_class)) {
            $this->andWhere([$this->tableName . '.model_class' => $this->model_class]);
        }
        return parent::prepare($builder);
    }

    /**
     * @inheritdoc
     * @return UserCollection[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return UserCollection|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}