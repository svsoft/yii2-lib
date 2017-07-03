<?php

namespace svsoft\yii\modules\properties\models\data;

use svsoft\yii\modules\properties\components\types\FloatValue;
use svsoft\yii\modules\properties\components\types\IntegerValue;
use svsoft\yii\modules\properties\components\types\StringValue;
use Yii;
use yii\base\Exception;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "property".
 *
 * @property int $property_id
 * @property string $name
 * @property string $slug
 * @property int $model_type_id
 * @property int $group_id
 * @property int $type
 * @property int $multiple
 * @property int $active
 *
 * @property PropertyGroup $group
 * @property PropertyModelType $modelType
 * @property PropertyValue[] $propertyValues
 */
class Property extends \yii\db\ActiveRecord
{
    const TYPE_STRING = 1;
    const TYPE_INTEGER = 2;
    const TYPE_FLOAT = 3;
    const TYPE_TEXT = 4;
    const TYPE_TIMESTAMP = 5;

    static public $types = [];


    static public function getTypes($type = null)
    {
        if (!self::$types)
        {
            self::$types = [
                self::TYPE_STRING => [
                    'valueClass'=> StringValue::className(),
                    'name'=>'Строка',
                ],
                self::TYPE_INTEGER => [
                    'valueClass'=> IntegerValue::className(),
                    'name'=>'Целое число',
                ],
                self::TYPE_FLOAT => [
                    'valueClass'=> FloatValue::className(),
                    'name'=>'Действительное число',
                ],
                self::TYPE_TEXT => [
                    'valueClass'=> StringValue::className(),
                    'name'=>'Текст',
                ],
                self::TYPE_TIMESTAMP => [
                    'valueClass'=> StringValue::className(),
                    'name'=>'Дата/время',
                ],
            ];
        }

        if (!$type)
            return self::$types;

        if (empty(self::$types[$type]))
            throw new Exception('Property type "' . $type . '" does not exist');

        return self::$types[$type];
    }



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'property';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model_type_id', 'group_id', 'type', 'multiple', 'active'], 'integer'],
            [['model_type_id', 'type','name','slug'], 'required'],
            [['name', 'slug'], 'string', 'max' => 255],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => PropertyGroup::className(), 'targetAttribute' => ['group_id' => 'group_id']],
            [['model_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => PropertyModelType::className(), 'targetAttribute' => ['model_type_id' => 'model_type_id']],
            ['multiple', 'default', 'value' => false ],
            ['active', 'default', 'value' => true ],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'name',
                'slugAttribute' => 'slug',
                'immutable' => true,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'property_id' => 'Property ID',
            'name' => 'Name',
            'slug' => 'Slug',
            'model_type_id' => 'Model Type ID',
            'group_id' => 'Group ID',
            'type' => 'Type',
            'multiple' => 'Multiple',
            'active' => 'Active',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(PropertyGroup::className(), ['group_id' => 'group_id']);
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
        return $this->hasMany(PropertyValue::className(), ['property_id' => 'property_id']);
    }

    /**
     * Получаевт класс для значения свойства объекта в зависимости от типа
     */
    public function getValueClass()
    {
        if (!$type = Property::getTypes($this->type))
            throw new Exception('Property type does not exist');

        return $type['valueClass'];
    }
}
