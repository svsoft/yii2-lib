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
 * Прикрепляется к объекту ObjectProperty.
 * Абстрактный клас. Наследниеи класа получаюю значение свойства соответстующего типа
 * из модели models\data\PropertyValue
 *
 * @package svsoft\yii\modules\properties\components
 */
abstract class ObjectPropertyValue extends Model
{
    /**
     * @var PropertyValue
     */
    private $propertyValue;

    private $delete = false;

    static $indexNewItems = 0;

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

    public function formName()
    {
        $formName = parent::formName();

        if ($this->propertyValue->value_id)
            $formName .=  $this->propertyValue->value_id;
        else
        {
            self::$indexNewItems ++;

            $formName .= '#' . self::$indexNewItems;
        }


        return $formName;
    }

}