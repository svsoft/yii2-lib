<?php

namespace svsoft\yii\modules\properties\models\data;

use Yii;
use yii\behaviors\SluggableBehavior;

/**
 * TODO: сделать слуг уникальным и во всех остальных сущностях
 *
 * This is the model class for table "property_group".
 *
 * @property int $group_id
 * @property string $name
 * @property string $slug
 * @property int $model_type_id
 * @property int $require
 *
 * @property Property[] $properties
 * @property Property[] $activeProperties
 * @property PropertyModelType $modelType
 */
class PropertyGroup extends \yii\db\ActiveRecord
{

    private $_properties;

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
            ['require','boolean'],
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
            'group_id' => 'Ид',
            'name' => 'Название',
            'slug' => 'Код',
            'model_type_id' => 'Тип модели',
            'require' => 'Обязательное',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProperties()
    {
        if ($this->isNewRecord)
        {
            return $this->_properties;
        }

        return $this->hasMany(Property::className(), ['group_id' => 'group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActiveProperties()
    {
        return $this->getProperties()->andWhere(['active'=>1])->indexBy('property_id');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelType()
    {
        return $this->hasOne(PropertyModelType::className(), ['model_type_id' => 'model_type_id']);
    }


    public function setProperties($properties)
    {
        $this->_properties = $properties;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($this->require && $this->require != $changedAttributes['require'])
        {
            /**
             * @var $objects PropertyObject[]
             */
            $objects = PropertyObject::find()->andWhere(['model_type_id'=>$this->model_type_id])->all();

            foreach($objects as $object)
            {
                $object->link('groups', $this);
            }
        }
    }

}
