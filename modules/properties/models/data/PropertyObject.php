<?php

namespace svsoft\yii\modules\properties\models\data;

use svsoft\yii\modules\catalog\models\Product;
use svsoft\yii\modules\properties\components\ObjectProperty;
use svsoft\yii\modules\properties\components\ObjectPropertyValue;
use svsoft\yii\modules\properties\models\forms\PropertyForm;
use svsoft\yii\modules\properties\queries\PropertyObjectQuery;
use svsoft\yii\modules\properties\traits\PropertiesTrait;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

/**
 * TODO: Добавить событие после удаления
 *
 * This is the model class for table "property_object".
 *
 * @property int $object_id
 * @property int $model_id
 * @property int $model_type_id
 * @property PropertyModelType $modelType
 * @property PropertyValue[] $propertyValues
 * @property PropertiesTrait $model
 * @property Product $product
 * @property PropertyForm[] $properties
 *
 */
class PropertyObject extends \yii\db\ActiveRecord
{

    /**
     * @var PropertyForm[]
     */
    private $_properties;

    private $_propertyForms;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'property_object';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model_id', 'model_type_id'], 'integer'],
            [['model_id', 'model_type_id'], 'required'],
            [['model_id', 'model_type_id'], 'unique', 'targetAttribute' => ['model_id', 'model_type_id']],
            [['model_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => PropertyModelType::className(), 'targetAttribute' => ['model_type_id' => 'model_type_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'object_id' => 'Object ID',
            'model_id' => 'Model ID',
            'model_type_id' => 'Model Type ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelType()
    {
        return $this->hasOne(PropertyModelType::className(), ['model_type_id' => 'model_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPropertyValues()
    {
        return $this->hasMany(PropertyValue::className(), ['object_id' => 'object_id']);
    }

    /**
     * Получает массив свойств привязанны к объекту
     *
     * @return \svsoft\yii\modules\properties\components\ObjectProperty[]
     */
    public function getProperties()
    {
        if ($this->_properties === null)
        {
            $properties = $this->modelType->getProperties()->indexBy('property_id')->all();

            $propertyValues = $this->propertyValues;

            $groupByPropertyId = [];
            foreach($propertyValues as $value)
            {
                $groupByPropertyId[$value['property_id']][] = $value;
            }

            foreach($properties as $propertyId => $property)
            {
                $values = ArrayHelper::getValue($groupByPropertyId, $propertyId, []);

                $PropertyForm = PropertyForm::createForm($this, $property, $values);

                $this->_properties[$propertyId] = $PropertyForm;
            }
        }

        return $this->_properties;
    }

    /**
     * @param $propertyId
     *
     * @return ObjectProperty|null
     */
    public function getPropertyById($propertyId)
    {
        return ArrayHelper::getValue($this->_properties, $propertyId);

    }

    /**
     * @param $slug
     *
     * @return PropertyForm|null
     */
    public function getPropertyBySlug($slug)
    {
        foreach($this->properties as $property)
        {
            if ($property->property->slug == $slug)
                return $property;
        }

        return null;
    }

    /**
     * Возвращает список свойств ввиде массива
     *
     * @return array
     */
    public function getPropertiesAsArray()
    {
        $return = [];
        foreach($this->getProperties() as $property)
        {
            $return[] = $property->asArray();
        }

        return $return;
    }

    /**
     * Возвращает список ввиде массива- ключ - название свойства, значение - список значений
     *
     * @return array
     */
    public function getPropertyAsList()
    {
        $return = [];
        foreach($this->getProperties() as $property)
        {
            $return[$property->property->name] = $property->property->multiple ? $property->values : $property->value;

        }

        return $return;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        // Для новой записи проставляем object_id для все значений свойств
        if ($insert)
        {
            foreach($this->properties as $property)
            {
                foreach($property->propertyValues as $propertyValue)
                {
                    $propertyValue->object_id = $this->object_id;
                }
            }
        }
    }

    public function validateProperties()
    {
        $valid = true;

        foreach($this->properties as $property)
        {
            if (!$property->validate())
            {
                $valid = false;
            }
        }

        return $valid;
    }

    public function validate($attributeNames = null, $clearErrors = true)
    {
        return parent::validate($attributeNames, $clearErrors);
    }

    public function beforeValidate()
    {
        if (!parent::beforeValidate())
            return false;


        return true;
    }

    /**
     * Сохранение значений свойств
     *
     * @return bool
     * @throws Exception
     */
    public function saveProperties()
    {
        // Сохраняем значения всех свойств
        foreach($this->properties as $property)
        {
            if (!$property->save())
            {
                foreach($property->errors as $error)
                {
                    $this->addError('properties', $error);
                }
            }
        }

        return true;
    }

    /**
     * Ищет по ид типа модели и ид модель объект для связи со свойствами.
     * Если не нашел создает запись в БД и возвращает объект
     *
     * @param $model_type_id
     * @param $model_id
     *
     * @return PropertyObject|static
     */
    static function findOneElseInsert($model_type_id, $model_id)
    {
        $condition = ['model_type_id'=>$model_type_id, 'model_id'=>$model_id];

        $object = parent::findOne($condition);

        if (!$object)
        {
            $object = new self($condition);
            $object->save();
        }

        return $object;
    }

    /**
     * @inheritdoc
     * @return PropertyObjectQuery the newly created [[ActiveQuery]] instance.
     */
    static function find()
    {
        return \Yii::createObject(PropertyObjectQuery::className(), [get_called_class()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModel()
    {
        $class = $this->modelType->class;

        return $this->hasOne($class, [$class::primaryKey()[0] => 'model_id']);
    }

    /**
     * До удаления удаляем все привязанные значения свойств
     *
     * @return bool
     */
    public function beforeDelete()
    {
        if (!parent::beforeDelete())
            return false;

        // Удаляем все значения всех свойств
        foreach($this->properties as $property)
        {
            foreach($property->propertyValues as $propertyValue)
            {
                $propertyValue->delete();
            }
        }

        return true;
    }
}
