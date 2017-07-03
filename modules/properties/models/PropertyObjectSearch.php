<?php

namespace svsoft\yii\modules\properties\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use svsoft\yii\modules\properties\models\data\PropertyObject;

/**
 * PropertyObjectSearch represents the model behind the search form of `svsoft\yii\modules\properties\models\data\PropertyObject`.
 */
class PropertyObjectSearch extends PropertyObject
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['object_id', 'model_id', 'model_type_id'], 'integer'],
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
        $query = PropertyObject::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'object_id' => $this->object_id,
            'model_id' => $this->model_id,
            'model_type_id' => $this->model_type_id,
        ]);

        return $dataProvider;
    }
}
