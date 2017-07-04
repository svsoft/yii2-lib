<?php

namespace svsoft\yii\modules\catalog\models;

use svsoft\yii\modules\properties\behaviors\PropertiesBehavior;
use svsoft\yii\modules\properties\traits\Properties;
use svsoft\yii\modules\properties\traits\PropertiesTrait;
use Yii;
use svsoft\yii\modules\main\files\FileAttributeBehavior;
use svsoft\yii\modules\main\files\FileAttributeTrait;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "catalog_product".
 *
 * @property integer $product_id
 * @property integer $category_id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property string $images
 * @property integer $active
 * @property integer $created
 * @property integer $updated
 * @property double $price
 * @property double $count
 * @property string $measure
 *
 * @property Category $category
 */
class Product extends \yii\db\ActiveRecord
{
    use FileAttributeTrait;
    use PropertiesTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'active', 'created', 'updated'], 'integer'],
            [['name'], 'required'],
            [['description', 'images'], 'string'],
            [['price', 'count'], 'number'],
            [['name', 'slug', 'measure'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'category_id']],
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
            [
                'class'=>TimestampBehavior::className(),
                'createdAtAttribute'=>'created',
                'updatedAtAttribute'=>'updated',
            ],
            [
                'class'=>FileAttributeBehavior::className(),
                'attributes' => ['images'],
            ],
            [
                'class' => PropertiesBehavior::className(),
                'getId' => function($model) { return $model->product_id;  },
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_id' => 'ID',
            'category_id' => 'Катеория',
            'name' => 'Наименование',
            'slug' => 'Код',
            'description' => 'Описание',
            'images' => 'Картинки',
            'active' => 'Активный',
            'created' => 'Дата создания',
            'updated' => 'Дата обновления',
            'price' => 'Цена',
            'count' => 'Количество',
            'measure' => 'Мера',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['category_id' => 'category_id']);
    }
}
