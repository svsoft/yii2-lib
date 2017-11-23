<?php

namespace svsoft\yii\modules\catalog\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * CategorySearch represents the model behind the search form of `svsoft\yii\modules\catalog\models\Category`.
 */
class CategorySearch extends Category
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'parent_id', 'active', 'created', 'updated', 'sort', 'slug_lock'], 'integer'],
            [['name', 'slug', 'images', 'slug_chain'], 'safe'],
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
        $query = Category::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andWhere(['parent_id'=>$this->parent_id]);

        // grid filtering conditions
        $query->andFilterWhere([
            'category_id' => $this->category_id,
            'active' => $this->active,
            'created' => $this->created,
            'updated' => $this->updated,
            'sort' => $this->sort,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'images', $this->images])
            ->andFilterWhere(['like', 'slug_chain', $this->slug_chain]);

        return $dataProvider;
    }
}
