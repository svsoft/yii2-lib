<?php
namespace svsoft\yii\modules\properties\components;

use svsoft\yii\modules\catalog\models\Product;
use svsoft\yii\modules\properties\models\data\Property;
use svsoft\yii\modules\properties\models\data\PropertyGroup;
use svsoft\yii\modules\properties\models\data\PropertyModelType;
use Yii;
use svsoft\yii\modules\shop\models\CartItem;
use yii\base\Component;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

class PropertyHelper
{
    function init()
    {
        parent::init();
    }


    /**
     * @param bool $nullItem
     *
     * @return array
     */
    static function getModelTypeList($nullItem = false)
    {
        return self::getModelList(PropertyModelType::find(), 'model_type_id', 'name', $nullItem);
    }


    /**
     * @param $query ActiveQuery
     * @param $fieldKey
     * @param $fieldValue
     */
    private static function getModelList($query, $fieldKey, $fieldValue, $nullItem = false)
    {
        $items = $query->asArray()->select([$fieldKey, $fieldValue])->all();

        $list = ArrayHelper::map($items, $fieldKey, $fieldValue);

        if ($nullItem !== false)
            $list = ArrayHelper::merge([''=>$nullItem], $list);

        return $list;

    }

    static function getGroupList($modelTypeId, $nullItem = false)
    {
        return self::getModelList(PropertyGroup::find()->where(['model_type_id'=>$modelTypeId]), 'group_id', 'name', $nullItem);
    }

    static function getTypeList($nullItem)
    {
        $types = Property::getTypes();

        $list = ArrayHelper::map($types, 'type_id', 'name');

        if ($nullItem !== false)
            $list = ArrayHelper::merge([''=>$nullItem], $list);

        return $list;
    }



}