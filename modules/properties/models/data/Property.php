<?php

namespace svsoft\yii\modules\properties\models\data;

use svsoft\yii\modules\properties\components\types\FloatValue;
use svsoft\yii\modules\properties\components\types\IntegerValue;
use svsoft\yii\modules\properties\components\types\StringValue;
use svsoft\yii\modules\properties\components\types\BooleanValue;
use svsoft\yii\modules\properties\models\forms\types\PropertyBoolean;
use svsoft\yii\modules\properties\models\forms\types\PropertyFloat;
use svsoft\yii\modules\properties\models\forms\types\PropertyInteger;
use svsoft\yii\modules\properties\models\forms\types\PropertyString;
use Yii;
use yii\base\Exception;
use yii\behaviors\SluggableBehavior;
use yii\db\Transaction;
use yii\helpers\ArrayHelper;

/**
 * TODO: При смене типа копировать значения из одной колонки в другую
 *
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
    const TYPE_STRING    = 1;
    const TYPE_INTEGER   = 2;
    const TYPE_FLOAT     = 3;
    const TYPE_TEXT      = 4;
    const TYPE_TIMESTAMP = 5;
    const TYPE_BOOLEAN   = 6;

    static public $types = [];


    /**
     * @var Transaction
     */
    private $transaction;
    /**
     * Возвращает название колонки в таблице property_value где хранятся значения типа $type
     *
     * @param $type
     *
     * @return mixed
     * @throws Exception
     */
    static public function columnNameByType($type)
    {
        static $columnNames = [
            Property::TYPE_STRING       => 'string_value',
            Property::TYPE_INTEGER      => 'int_value',
            Property::TYPE_FLOAT        => 'float_value',
            Property::TYPE_TEXT         => 'text_value',
            Property::TYPE_TIMESTAMP    => 'timestamp_value',
            Property::TYPE_BOOLEAN      => 'int_value',
        ];

        $type = self::getTypes($type);

        $columnName = $type['columnName'];

        return $columnName;
    }

    public function getColumnName()
    {
        return self::columnNameByType($this->type);
    }


    static public function getTypes($type = null)
    {
        if (!self::$types)
        {
            self::$types = [
                self::TYPE_STRING => [
                    'valueClass'=> StringValue::className(),
                    'name'=>'Строка',
                    'columnName'=> 'string_value',
                    'typeClass'=> PropertyString::className(),
                ],
                self::TYPE_INTEGER => [
                    'valueClass'=> IntegerValue::className(),
                    'name'=>'Целое число',
                    'columnName'=> 'int_value',
                    'typeClass'=> PropertyInteger::className(),
                ],
                self::TYPE_FLOAT => [
                    'valueClass'=> FloatValue::className(),
                    'name'=>'Действительное число',
                    'columnName'=> 'float_value',
                    'typeClass'=> PropertyFloat::className(),
                ],
                self::TYPE_TEXT => [
                    'valueClass'=> StringValue::className(),
                    'name'=>'Текст',
                    'columnName'=> 'text_value',
                    'typeClass'=> PropertyString::className(),
                ],
                self::TYPE_TIMESTAMP => [
                    'valueClass'=> StringValue::className(),
                    'name'=>'Дата/время',
                    'columnName'=> 'timestamp_value',
                    'typeClass'=> PropertyInteger::className(),
                ],
                self::TYPE_BOOLEAN => [
                    'valueClass'=> BooleanValue::className(),
                    'name'=>'Да/Нет',
                    'columnName'=> 'int_value',
                    'typeClass'=> PropertyBoolean::className(),
                ],
            ];

            foreach(self::$types as $typeId=>&$item)
            {
                $item['type_id'] = $typeId;
            }
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
            [['model_type_id', 'type','name'], 'required'],
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
            'name' => 'Название',
            'slug' => 'Код',
            'model_type_id' => 'Тип объекта',
            'group_id' => 'Группа',
            'type' => 'Тип',
            'multiple' => 'Множественное',
            'active' => 'Активное',
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

    /**
     * Получаевт название типа текущего свойства
     */
    public function getTypeName()
    {
        if (!$type = Property::getTypes($this->type))
            throw new Exception('Property type does not exist');

        return $type['name'];
    }

    /**
     * Получает имя класса реализующие функционал в соответсвие с типом
     */
    public function getTypeClass()
    {
        if (!$type = Property::getTypes($this->type))
            throw new Exception('Property type does not exist');

        return $type['typeClass'];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    static function findActive()
    {
        return parent::find()->where(['active'=>1]);
    }

    /**
     * Открывает транзакцию которая закрывактся в afterSave
     *
     * @param bool $insert
     *
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert))
            return false;

        $this->transaction = Yii::$app->db->beginTransaction();

        return true;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if (!$insert)
        {
            if (isset($changedAttributes['type']) && ($changedAttributes['type'] != $this->type))
            {
                $currentColumn = self::columnNameByType($changedAttributes['type']);
                $nextColumn = self::columnNameByType($this->type);


                if (!PropertyValue::updateAll([$nextColumn => new \yii\db\Expression("{$currentColumn}"), $currentColumn=>null], ['property_id'=>$this->property_id]))
                    $this->transaction->rollback();
            }
        }

        if ($this->transaction->getIsActive())
            $this->transaction->commit();
    }
}
