<?php

namespace svsoft\yii\modules\properties\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use svsoft\yii\modules\properties\models\data\PropertyModelType;

/**
 * PropertyModelTypeSearch represents the model behind the search form of `svsoft\yii\modules\properties\models\data\PropertyModelType`.
 */
class PropertyModelTypeSearch extends PropertyModelType
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model_type_id'], 'integer'],
            [['name', 'slug', 'class'], 'safe'],
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
        $query = PropertyModelType::find();

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
            'model_type_id' => $this->model_type_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'class', $this->class]);

        return $dataProvider;
    }
}
