<?php

namespace svsoft\yii\modules\properties\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use svsoft\yii\modules\properties\models\data\PropertyValue;

/**
 * PropertyValueSearch represents the model behind the search form of `svsoft\yii\modules\properties\models\data\PropertyValue`.
 */
class PropertyValueSearch extends PropertyValue
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['value_id', 'property_id', 'object_id', 'int_value', 'timestamp_value'], 'integer'],
            [['string_value', 'text_value'], 'safe'],
            [['float_value'], 'number'],
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
        $query = PropertyValue::find();

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
            'value_id' => $this->value_id,
            'property_id' => $this->property_id,
            'object_id' => $this->object_id,
            'int_value' => $this->int_value,
            'float_value' => $this->float_value,
            'timestamp_value' => $this->timestamp_value,
        ]);

        $query->andFilterWhere(['like', 'string_value', $this->string_value])
            ->andFilterWhere(['like', 'text_value', $this->text_value]);

        return $dataProvider;
    }
}
