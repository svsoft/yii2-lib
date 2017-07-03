<?php

namespace svsoft\yii\modules\properties\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use svsoft\yii\modules\properties\models\data\Property;

/**
 * PropertySearch represents the model behind the search form of `svsoft\yii\modules\properties\models\data\Property`.
 */
class PropertySearch extends Property
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['property_id', 'model_type_id', 'group_id', 'type', 'multiple', 'active'], 'integer'],
            [['name', 'slug'], 'safe'],
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
        $query = Property::find();

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
            'property_id' => $this->property_id,
            'model_type_id' => $this->model_type_id,
            'group_id' => $this->group_id,
            'type' => $this->type,
            'multiple' => $this->multiple,
            'active' => $this->active,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'slug', $this->slug]);

        return $dataProvider;
    }
}
