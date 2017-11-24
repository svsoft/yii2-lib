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

    /**
     * Получает список категорий со структурой
     *
     * @param null $parentId
     * @param string $root
     *
     * @return array
     */
    static public function getCategoryListWithStructure($parentId = null, $root = '-Каталог-')
    {
        $list = [];
        Category::root()->walkChildren(function (Category $category, $key, $index, $count) use (&$list)  {

            $level = $category->getLevel();

            $char = '';
            if ($level)
            {
                $char = str_repeat('│ ', $level - 1);

                if ($index == $count-1)
                    $char .= '└−';
                else
                    $char .= '├−';
            }

            $list[$category->category_id] = $char . $category->name;
        });

        if (!$root)
            return $list;

        return [null=>$root] + $list;
    }
}