<?php

namespace yuncms\admin\models;

use Carbon\Carbon;
use Yii;
use yii\data\ActiveDataProvider;
use yuncms\models\Volume;
use yii\base\Model;

/**
 * VolumeSearch represents the model behind the search form about `yuncms\models\Volume`.
 */
class VolumeSearch extends Volume
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['pub'], 'boolean'],
            [['identity', 'name', 'className', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Volume::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                    'id' => SORT_ASC,
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'pub' => $this->pub,
            'status' => $this->status,
            'identity' => $this->identity,
        ]);

        if ($this->created_at !== null) {
            $date = Carbon::parse($this->created_at);
            $query->andWhere(['between', 'created_at', $date->timestamp, $date->addDays(1)->timestamp]);
        }


        if ($this->updated_at !== null) {
            $date = Carbon::parse($this->updated_at);
            $query->andWhere(['between', 'updated_at', $date->timestamp, $date->addDays(1)->timestamp]);
        }

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'className', $this->className]);

        return $dataProvider;
    }
}
