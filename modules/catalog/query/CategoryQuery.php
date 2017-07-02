<?php


namespace svsoft\yii\modules\catalog\query;


use yii\db\ActiveQuery;

class CategoryQuery extends ActiveQuery
{
    public function active()
    {
        $this->andWhere(['active'=>true]);
    }
}