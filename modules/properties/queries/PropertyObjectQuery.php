<?php


namespace svsoft\yii\modules\properties\queries;


use svsoft\yii\modules\properties\models\data\Property;
use svsoft\yii\modules\properties\models\data\PropertyObject;
use svsoft\yii\modules\properties\models\data\PropertyValue;
use yii\base\Exception;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

class PropertyObjectQuery extends ActiveQuery
{

    private $propertyConditions = [];

    /**
     * Добавляет условия для фильтрования в $this->propertyConditions Сейчас она добавляет 2 left join
     *
     * @param $condition
     *
     * @return $this
     */
    public function andProperty($condition)
    {
        $this->propertyConditions = ArrayHelper::merge($this->propertyConditions, $condition);

        return $this;
    }

    public function createCommand($db = null)
    {
        if ($this->propertyConditions)
            $this->filterByProperties();

        return parent::createCommand($db);
    }

    /**
     * TODO: Сделать чтобы эта функция добавляа IN по object_id
     *
     * @throws Exception
     */
    private function filterByProperties()
    {
        $arrayId = $arraySlug = [];

        $normalizePropertyConditions = [];
        foreach($this->propertyConditions as $key=>$item)
        {
            // то в вормате [operator,id|slug,...]
            if (is_numeric($key))
            {
                $property = $item[1];
                $normalizePropertyConditions[$property] = $item;
            }
            else
            {
                $property = $key;
                $normalizePropertyConditions[$property] = ['=',$property, $item];
            }

            if (is_numeric($property))
            {
                $arrayId[] = $property;
            }
            else
            {
                $arraySlug[] = $property;
            }
        }

        $where = ['or',['property_id'=>$arrayId], ['slug'=>$arraySlug]];

        if (!$arrayId)
        {
            $where = $where[2];
        }
        elseif (!$arrayId)
        {
            $where = $where[1];
        }

        $propertyItems = Property::find()->andWhere(['active'=>1])->andWhere($where)->select(['property_id','type','slug'])->asArray()->all();

        $arId = ArrayHelper::getColumn($propertyItems,'property_id');
        $arSlug = ArrayHelper::getColumn($propertyItems,'slug');

        // Нормализация зсвойств
        foreach($normalizePropertyConditions as $property=>$condition)
        {

            // то в вормате [operator,field,...]
            if (is_numeric($property))
            {
                $index = array_search($property, $arId);
            }
            else
            {
                $index = array_search($property, $arSlug);
            }

            if ($index === false)
                throw new Exception('Property ' . $property . ' is not found');

            $propertyItem = ArrayHelper::getValue($propertyItems, $index);

            $propertyId = $propertyItem['property_id'];

            unset($normalizePropertyConditions[$property]);

            $condition[1] = Property::columnNameByType($propertyItem['type']);

            $normalizePropertyConditions[$propertyId] = $condition;
        }

        $p = Property::tableName();
        $po = PropertyObject::tableName();
        $pv = PropertyValue::tableName();

        // TODO: Сделать отдельным запросом получение object_id
//        $rows = (new \yii\db\Query())
//            ->select(['id', 'email'])
//            ->from('user')
//            ->where(['last_name' => 'Smith'])
//            ->limit(10)
//            ->all();


        $where = ['or'];
        foreach($normalizePropertyConditions as $propertyId=>$condition)
        {
            if ($condition[0] == '!=' || $condition[0] == '<>')
            {
                $condition = ['or', $condition, ['IS', $condition[1], null]];
            }

            $where[] = ['and',[$p.'.property_id'=>$propertyId], $condition];
        }

        $query = $this->leftJoin($p, "{$p}.model_type_id = {$po}.model_type_id")
            ->leftJoin($pv, "{$pv}.object_id = {$po}.object_id AND {$pv}.property_id = {$p}.property_id")
            ->andWhere($where);

        $query->groupBy($po.'.object_id');;

        if (count($where) > 2)
            $query->having(["count({$pv}.object_id)"=>count($where) - 1]);
    }
}