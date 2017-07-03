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
use yii\base\Exception;
use yii\base\Model;
use yii\base\Object;

/**
 * Class Value
 *
 * @property string name
 * @property string slug
 * @property string|int|float value
 * @property Property $property
 *
 * @package svsoft\yii\modules\properties\models
 */
abstract class ObjectPropertyValue extends Object
{
    /**
     * @var PropertyValue
     */
    private $propertyValue;

    private $delete = false;

    public function __construct(PropertyValue $propertyValue)
    {
        $this->propertyValue  = $propertyValue;

        parent::__construct([]);
    }

    public function getPropertyValue()
    {
        return $this->propertyValue;
    }

    /**
     * Возвращает название колонки в таблице property_value где будет хранится свойство
     *
     * @return mixed
     */
    abstract public function getColumnName();

    /**
     * безопасный вывод свойства.
     *
     * @return mixed
     */
    abstract public function displayValue();

    /**
     * Получает значение свойства
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->propertyValue->getAttribute($this->getColumnName());
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->propertyValue->setAttribute($this->getColumnName(), $value);

        return $this;
    }

    /**
     * Помечает на удаление. При сохранении ObjectProperty
     *
     */
    public function delete()
    {
        $this->delete = true;
    }

    /**
     * @return bool
     */
    public function isDeleted()
    {
        return $this->delete;
    }

    public function __toString()
    {
        return $this->displayValue();
    }
}