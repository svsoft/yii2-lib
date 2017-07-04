<?php
/**
 * Created by PhpStorm.
 * User: viktor
 * Date: 13.06.2017
 * Time: 23:17
 */

namespace svsoft\yii\modules\properties\models\forms;

use svsoft\yii\modules\properties\models\data\Property;
use svsoft\yii\modules\properties\models\data\PropertyObject;
use svsoft\yii\modules\properties\models\data\PropertyValue;
use svsoft\yii\modules\properties\models\forms\types\FloatValue;
use svsoft\yii\modules\properties\models\forms\types\StringValue;
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
abstract class PropertyValueForm extends Model
{
    /**
     * @var Property
     */
    public $property;

    /**
     * @var Object
     */
    public $object;

    /**
     * @var PropertyValue
     */
    private $propertyValue;

    private static $indexNewItems = 0;

    public $value;

    public function getPropertyValue()
    {
        return $this->propertyValue;
    }

    /**
     * @param PropertyValue $propertyValue
     *
     * @return PropertyValue
     */
    public function setPropertyValue(PropertyValue $propertyValue)
    {
        $this->propertyValue = $propertyValue;

        $this->value = $this->propertyValue->getAttribute($this->getColumnName());
    }

    /**
     * Возвращает название колонки в таблице property_value где будет хранится свойство
     *
     * @return mixed
     */
    abstract public function getColumnName();

    public function formName()
    {
        $formName = parent::formName();

        $formName = 'Property-' . $this->propertyValue->property->slug;

        if ($this->propertyValue->value_id)
        {
            $formName .= '-' . $this->propertyValue->value_id;
        }
        else
        {
            self::$indexNewItems ++;

            $formName .= '#' . self::$indexNewItems;
        }

        return $formName;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['value'], 'required'],
            ['propertyValue', 'checkPropertyType', 'skipOnEmpty'=> false],
        ];
    }

    /**
     * Проверяет существование propertyValue
     *
     * и соответствие названия колонок в бд куда сохраняется значение. у свойства и у модели формы.
     *
     *
     * @return bool
     */
    public function checkPropertyType()
    {
        if (!$this->propertyValue)
        {
            $this->addError('propertyValue', 'Property propertyValue is not set');
            return false;
        }

        if ($this->propertyValue->property->getColumnName() != $this->getColumnName())
        {
            $this->addError('propertyValue', 'Property type does not match type of value');
            return false;
        }

    }

    public function save()
    {
        if (!$this->validate())
            return false;

        $column = $this->getColumnName();

        $this->propertyValue->setAttribute($column, $this->value);

        // Если запись не новая и свойство не установлено то удаляем объект значения
        if (!$this->propertyValue->isNewRecord && !$this->value)
        {
            $this->propertyValue->delete();
            return true;
        }

        return $this->propertyValue->save();
    }

    /**
     * @param PropertyObject $object
     *
     * @return PropertyValue
     * @throws Exception
     */
    public function createPropertyValue(PropertyObject $object)
    {
        $this->object = $object;

        if ($this->propertyValue)
            throw new Exception('Property "propertyValue" has already set');

        if (!$this->property)
            throw new Exception('Property "property" is not set');

        $propertyValue = new PropertyValue();
        $propertyValue->property_id = $this->property->property_id;
        // $propertyValue->link('property', $this->property);
        // $propertyValue->link('object', $object);
        $propertyValue->object_id = $object->object_id;

        $this->propertyValue = $propertyValue;

        return $this->propertyValue;
    }

    /**
     * @param $property
     *
     * @return self
     */
    static public function createForm($property)
    {
        $class = StringValue::className();

        static $classes = [
            Property::TYPE_STRING => 'svsoft\yii\modules\properties\models\forms\types\StringValue',
            Property::TYPE_FLOAT => 'svsoft\yii\modules\properties\models\forms\types\FloatValue',
        ];

        $form = new $classes[$property->type];
        $form->property = $property;

        return $form;
    }
}