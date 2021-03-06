<?php

namespace svsoft\yii\modules\properties\models\data;

use svsoft\yii\modules\catalog\models\Product;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "property_value".
 *
 * @property int $value_id
 * @property int $property_id
 * @property int $object_id
 * @property string $string_value
 * @property string $text_value
 * @property int $int_value
 * @property double $float_value
 * @property int $timestamp_value
 *
 * @property PropertyObject $object
 * @property Property $property
 * @value mixed $property
 */
class PropertyValue extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'property_value';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['property_id', 'object_id', 'int_value', 'timestamp_value'], 'integer'],
            [['property_id', 'object_id'], 'required'],
            [['text_value'], 'string'],
            [['float_value'], 'number'],
            [['string_value'], 'string', 'max' => 255],
            [['object_id'], 'exist', 'skipOnError' => true, 'targetClass' => PropertyObject::className(), 'targetAttribute' => ['object_id' => 'object_id']],
            [['property_id'], 'exist', 'skipOnError' => true, 'targetClass' => Property::className(), 'targetAttribute' => ['property_id' => 'property_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'value_id' => 'Value ID',
            'property_id' => 'Property ID',
            'object_id' => 'Object ID',
            'string_value' => 'String Value',
            'text_value' => 'Text Value',
            'int_value' => 'Int Value',
            'float_value' => 'Float Value',
            'timestamp_value' => 'Timestamp Value',
            'property' => 'Свойство',
            'propertyType' => 'Тип',
            'model' => 'Объект',
            'modelType'=>'Тип объекта',
            'value'=>'Значение'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getObject()
    {
        return $this->hasOne(PropertyObject::className(), ['object_id' => 'object_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProperty()
    {
        return $this->hasOne(Property::className(), ['property_id' => 'property_id']);
    }

    /**
     * Возвращает занчени в соответстии с типом совйства
     */
    public function getValue()
    {
        $column = $this->property->getColumnName();

        return $this->getAttribute($column);
    }

    /**
     * Возвращает занчени в соответстии с типом совйства
     */
    public function setValue($value)
    {
        $column = $this->property->getColumnName();

        return $this->setAttribute($column, $value);
    }


    /**
     * @param $object_id
     * @param $property_id
     *
     * @return PropertyValue[]
     */
    static public function findByObjectAndProperty($object_id, $property_id)
    {
        return PropertyValue::find()->where(['property_id'=>$property_id, 'object_id'=>$object_id])->indexBy('value_id')->all();
    }

    static function find()
    {
        return parent::find()->with('property'); // TODO: Change the autogenerated stub
    }
}
