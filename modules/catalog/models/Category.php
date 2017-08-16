<?php

namespace svsoft\yii\modules\catalog\models;

use svsoft\yii\modules\catalog\query\CategoryQuery;
use svsoft\yii\modules\main\files\FileAttributeBehavior;
use svsoft\yii\modules\main\files\FileAttributeTrait;
use svsoft\yii\modules\properties\behaviors\PropertiesBehavior;
use svsoft\yii\modules\properties\traits\PropertiesTrait;
use svsoft\yii\traits\SluggableTrait;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "catalog_category".
 *
 * @property integer $category_id
 * @property integer $parent_id
 * @property string $name
 * @property string $slug
 * @property string $slug_chain - цепочка кодов
 * @property integer $slug_lock - флаг блокировки изменения slug
 * @property string $images
 * @property integer $active
 * @property integer $sort
 *
 * @property Category $parent
 * @property Category[] $categories
 * @property Product[] $products
 * @property Product[] $activeProducts
 */
class Category extends \yii\db\ActiveRecord
{
    use FileAttributeTrait;
    use SluggableTrait;
    use PropertiesTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id','sort'], 'integer'],
            [['active','slug_lock'], 'boolean'],
            [['name'], 'required'],
            [['images'], 'string'],
            [['name', 'slug'], 'string', 'max' => 255],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['parent_id' => 'category_id']],
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
                'getId' => function($model) { return $model->category_id;  },
                'nameAttribute' => 'name',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_id' => 'Category ID',
            'parent_id' => 'Parent ID',
            'name' => 'Name',
            'slug' => 'Slug',
            'images' => 'Images',
            'active' => 'Active',
        ];
    }

    public function beforeValidate()
    {
        if (!parent::beforeValidate())
            return false;

        $this->fillSlugChain();

        // блокируем изменение slug
        if (!$this->isNewRecord && $this->slug_lock)
        {
            $this->slug = $this->getOldAttribute('slug');
        }

        return true;
    }

    public function afterValidate()
    {
        parent::afterValidate();

        // Добавляем ошибки от slug_chain в slug
        if ($this->getFirstError('slug_chain'))
            $this->addError('slug', $this->getFirstError('slug_chain'));
    }

    public function fillSlugChain()
    {
        $chain = $this->getParentChain();

        $slug_chain = '';
        foreach($chain as $category)
        {
            $slug_chain .= $category->slug . '/';
        }

        $slug_chain .= $this->slug;

        $this->slug_chain = $slug_chain;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Category::className(), ['category_id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['parent_id' => 'category_id']);
    }

    /**
     * @inheritdoc
     * @return CategoryQuery the newly created [[ActiveQuery]] instance.
     */
    static public function find()
    {
        return Yii::createObject(CategoryQuery::className(), [get_called_class()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['category_id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActiveProducts()
    {
        return $this->getProducts()->andWhere(['active'=>1]);
    }

    /**
     * Получает цепочку родительских категорий
     *
     * @return Category[]
     */
    public function getParentChain()
    {
        $chain = [];

        if (!$this->parent_id)
            return [];

        $category = $this;
        do
        {
            $category = $category->parent;
            $chain[$category->category_id] = $category;
        }
        while($category->parent_id);

        return array_reverse($chain);
    }

    public function getChain()
    {
        $chain = $this->getParentChain();

        $chain[$this->category_id] = $this;

        return $chain;
    }
}
