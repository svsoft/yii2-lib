<?php
/**
 * Created by PhpStorm.
 * User: viktor
 * Date: 13.06.2017
 * Time: 23:17
 */

namespace svsoft\yii\modules\properties\components;

use svsoft\yii\modules\properties\models\data\Property;
use svsoft\yii\modules\properties\models\data\PropertyObject;
use svsoft\yii\modules\properties\models\data\PropertyValue;
use Yii;
use yii\base\Object;
use yii\helpers\ArrayHelper;

/**
 * Class ObjectProperty
 *
 * @property string name
 * @property string slug
 * @property Property $property
 *
 * @package svsoft\yii\modules\properties\models
 */
class ObjectProperty extends Object
{
    /**
     * @var Property
     */
    private $property;
    /**
     * @var PropertyObject
     */
    private $propertyObject;

    /**
     * @var PropertyValue[]
     */
    private $propertyValues = [];

    /**
     * @var ObjectPropertyValue[]
     */
    private $values;

    public function __construct(PropertyObject $propertyObject, Property $property, $propertyValues = [])
    {
        $this->property        = $property;
        $this->propertyObject  = $propertyObject;
        $propertyValues        = $propertyValues ? $propertyValues : [];

        $this->values = [];

        foreach($propertyValues as $propertyValue)
        {
             $objectPropertyValue = Yii::createObject($this->property->getValueClass(), [
                $propertyValue
            ]);

            $this->values[] = $objectPropertyValue;
        }

        parent::__construct([]);
    }

    /**
     * @return Property
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->property->name;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->property->slug;
    }

    /**
     * @return ObjectPropertyValue[]|array
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @return ObjectPropertyValue|mixed|null
     */
    public function getValue()
    {
        return ArrayHelper::getValue($this->values, 0, null);
    }

    /**
     * Добавляет значение свойста
     *
     * @param $value
     */
    public function addValue($value)
    {
        $propertyValue = new PropertyValue();

        // заполняем ид объект к кторому привязан значение
        $propertyValue->object_id = $this->propertyObject->object_id;
        // заполняе ид свойства к которому привязано значение
        $propertyValue->property_id = $this->property->property_id;

        /**
         * @var ObjectPropertyValue $objectPropertyValue;
         */
        $objectPropertyValue = Yii::createObject($this->property->getValueClass(), [
            $propertyValue
        ]);

        // Устанавливаем значение
        $objectPropertyValue->setValue($value);

        // пушим в массив значений
        $this->values[] = $objectPropertyValue;

        return $objectPropertyValue;
    }

    /**
     * Получает все значения
     *
     * @param $value
     *
     * @return ObjectPropertyValue|mixed|null
     */
    public function findValueByValue($value)
    {
        foreach($this->values as $objectPropertyValue)
        {
            if ($objectPropertyValue->getValue() == $value)
                return $objectPropertyValue;
        }

        return null;
    }

    /**
     * @param $value
     *
     * @return ObjectPropertyValue array
     */
    public function findValuesByValue($value)
    {
        $return = [];

        foreach($this->values as $objectPropertyValue)
        {
            if ($objectPropertyValue->getValue() == $value)
                $return[] = $objectPropertyValue;
        }

        return $return;
    }

    /**
     * @return array
     */
    public function getValuesAsArray()
    {
        $return = [];

        foreach($this->values as $value)
            $return[] = $value->getValue();

        return $return;
    }

    /**
     *  Проверяет заполнено ли свойство
     */
    public function isEmpty()
    {
        return $this->values ? false : true;
    }

    /**
     * Возвращает объект ввиде массива
     *
     * @return array
     */
    public function asArray()
    {
        $property = $this->property->getAttributes();
        $property['values'] = $this->getValuesAsArray();

        return $property;
    }
}