<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AssignmentSearch represents the model behind the search form about Assignment.
 * @package backend
 * @author Xu Tongle <xutongle@gmail.com>
 * @since 3.0
 */
class AdminAssignmentSearch extends Model
{
    public $id;
    public $username;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'username'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('admin', 'Username'),
            'name' => Yii::t('admin', 'Name'),
        ];
    }

    /**
     * Create data provider for Assignment model.
     * @param array $params
     * @param \yii\db\ActiveRecord $class
     * @param string $usernameField
     * @return \yii\data\ActiveDataProvider
     */
    public function search($params, $class, $usernameField)
    {
        $query = $class::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere(['like', $usernameField, $this->username]);
        return $dataProvider;
    }
}