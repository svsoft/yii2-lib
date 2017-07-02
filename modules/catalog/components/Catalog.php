<?php
namespace svsoft\yii\modules\catalog\components;

use svsoft\yii\modules\catalog\models\CatalogCategory;
use yii\base\Component;
use yii\helpers\ArrayHelper;

class Catalog extends Component
{
    public function getCategoryList($parentId = null)
    {
        $query = CatalogCategory::find();
        if ($parentId !== false)
            $query->andWhere(['parent_id'=>$parentId]);

        $categories = $query->select(['name','category_id'])->all();

        return ArrayHelper::map($categories, 'category_id', 'name');
    }
}