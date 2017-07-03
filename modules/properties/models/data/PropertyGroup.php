<?php

namespace svsoft\yii\modules\properties\models\data;

use Yii;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "property_group".
 *
 * @property int $group_id
 * @property string $name
 * @property string $slug
 * @property int $model_type_id
 *
 * @property Property[] $properties
 * @property PropertyModelType $modelType
 */
class PropertyGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'property_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model_type_id'], 'integer'],
            [['name', 'slug'], 'string', 'max' => 255],
            [['model_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => PropertyModelType::className(), 'targetAttribute' => ['model_type_id' => 'model_type_id']],
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
            'group_id' => 'Group ID',
            'name' => 'Name',
            'slug' => 'Slug',
            'model_type_id' => 'Model Type ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProperties()
    {
        return $this->hasMany(Property::className(), ['group_id' => 'group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelType()
    {
        return $this->hasOne(PropertyModelType::className(), ['model_type_id' => 'model_type_id']);
    }
}