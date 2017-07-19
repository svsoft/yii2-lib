<?php

namespace svsoft\yii\modules\shop\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use svsoft\yii\modules\shop\models\CartItem;

/**
 * CartItemSearch represents the model behind the search form of `svsoft\yii\modules\shop\models\CartItem`.
 */
class CartItemSearch extends CartItem
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id', 'product_id', 'order_id', 'user_id', 'created', 'updated'], 'integer'],
            [['session_id'], 'safe'],
            [['price', 'count'], 'number'],
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
        $query = CartItem::find();

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
            'item_id' => $this->item_id,
            'product_id' => $this->product_id,
            'order_id' => $this->order_id,
            'user_id' => $this->user_id,
            'price' => $this->price,
            'count' => $this->count,
            'created' => $this->created,
            'updated' => $this->updated,
        ]);

        $query->andFilterWhere(['like', 'session_id', $this->session_id]);

        return $dataProvider;
    }
}
