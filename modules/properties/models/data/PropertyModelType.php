<?php

namespace svsoft\yii\modules\properties\models\data;

use Yii;
use yii\behaviors\SluggableBehavior;

/**
 * TODO: Доавить уникальность на атрибут class
 *
 * This is the model class for table "property_model_type".
 *
 * @property int $model_type_id
 * @property string $name
 * @property string $slug
 * @property string $class
 *
 * @property Property[] $properties
 * @property Property[] $activeProperties
 * @property PropertyGroup[] $propertyGroups
 * @property PropertyObject[] $propertyObjects
 */
class PropertyModelType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'property_model_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'slug', 'class'], 'string', 'max' => 255],
            ['name', 'unique'],
            ['slug', 'unique'],
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
            'model_type_id' => 'Model Type ID',
            'name' => 'Name',
            'slug' => 'Slug',
            'class' => 'Class',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProperties()
    {
        return $this->hasMany(Property::className(), ['model_type_id' => 'model_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActiveProperties()
    {
        return $this->getProperties()->andWhere(['active'=>1]);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPropertyGroups()
    {
        return $this->hasMany(PropertyGroup::className(), ['model_type_id' => 'model_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPropertyObjects()
    {
        return $this->hasMany(PropertyObject::className(), ['model_type_id' => 'model_type_id']);
    }
}
