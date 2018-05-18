<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\models;

use yii\data\ActiveDataProvider;
use yii\base\Model;

/**
 * 用户搜索模型
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class UserSearch extends Model
{
    /** @var string */
    public $nickname;

    /** @inheritdoc */
    public function rules()
    {
        return [
            [['nickname'], 'string'],
        ];
    }

    /**
     * @param $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = User::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere(['like', 'nickname', $this->nickname]);
        return $dataProvider;
    }
}