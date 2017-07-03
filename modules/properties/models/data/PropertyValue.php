<?php

namespace svsoft\yii\modules\properties\models\data;

use Yii;

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
}
