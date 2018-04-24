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
use yuncms\user\models\User;
use Carbon\Carbon;

/**
 * UserSearch represents the model behind the search form about User.
 */
class UserSearch extends Model
{
    public $id;

    /** @var string */
    public $username;

    /** @var string */
    public $email;

    /** @var int */
    public $created_at;

    /** @var string */
    public $registration_ip;


    /** @inheritdoc */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['username', 'email', 'registration_ip', 'created_at'], 'safe'],
            ['created_at', 'default', 'value' => null],
        ];
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('yuncms', 'ID'),
            'username' => Yii::t('yuncms', 'Username'),
            'email' => Yii::t('yuncms', 'Email'),
            'created_at' => Yii::t('yuncms', 'Registration time'),
            'registration_ip' => Yii::t('yuncms', 'Registration ip'),
        ];
    }

    /**
     * @param $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = User::find()->orderBy(['id' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        if ($this->created_at !== null) {
            $date = Carbon::parse($this->created_at);
            $query->andWhere(['between', 'created_at', $date->timestamp, $date->addDays(1)->timestamp]);
        }

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['registration_ip' => $this->registration_ip]);

        return $dataProvider;
    }
}
