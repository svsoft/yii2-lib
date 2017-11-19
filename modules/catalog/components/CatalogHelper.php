<?php
namespace svsoft\yii\modules\catalog\components;

use svsoft\yii\modules\catalog\models\Category;
use yii\base\Component;
use yii\helpers\ArrayHelper;

class CatalogHelper extends Component
{
    static public function getCategoryList($parentId = null, $root = 'Без категории')
    {
        $query = Category::find();
        if ($parentId !== false)
            $query->andWhere(['parent_id'=>$parentId]);

        $categories = $query->select(['name','category_id'])->asArray()->all();

        $categories = ArrayHelper::map($categories, 'category_id', 'name');

        if (!$root)
            return $categories;

        return [null=>$root] + $categories;
    }

//    static public function getCategoryTree($parentId = false, $root = 'Без категории')
//    {
//        $query = Category::find();
//        if ($parentId !== false)
//            $query->andWhere(['parent_id'=>$parentId]);
//
//        $query->orderBy(['slug_chain'=>'ASC']);
//
//        $models = $query->all();
//
//        foreach($models as $model)
//        {
//            $chain = explode('/', $model->slug_chain);
//        }
//
//        var_dump($models);
//
//
//    }
}