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
use yii\helpers\ArrayHelper;

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
            [['parent_id'], 'parentValidator'],
        ];
    }

    /**
     * Проверяет чтоб parent_id не был дочерним и текущим элементами
     */
    public function parentValidator()
    {
        $this->category_id;

        $categories = static::findAllChildren($this->category_id);

        $ids = array_keys($categories);

        $ids[] = $this->category_id;

        if (in_array($this->parent_id, $ids))
            $this->addError('parent_id', 'Не допустимая родительская категория');
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
            'category_id' => 'ИД',
            'parent_id' => 'Родительская категория',
            'name' => 'Название',
            'slug' => 'Код',
            'images' => 'Картинка',
            'active' => 'Активность',
            'sort' => 'Сортировка',
            'created' => 'Дата создания',
            'updated' => 'Дата обновления',
        ];
    }

    public function beforeValidate()
    {
        if (!parent::beforeValidate())
            return false;

        $this->fillSlugChain();

        // блокируем изменение slug если установлен slug_lock
        if (!$this->isNewRecord && $this->slug_lock)
        {
            $this->slug = $this->getOldAttribute('slug');
        }

        return true;
    }

    /**
     * Обновляет slug_chain
     *
     * @return int
     */
    public function updateSlugChain()
    {
        $this->fillSlugChain();
        return $this->updateAttributes(['slug_chain']);
    }

    public function afterValidate()
    {
        parent::afterValidate();

        // Добавляем ошибки от slug_chain в slug
        if ($this->getFirstError('slug_chain'))
            $this->addError('slug', $this->getFirstError('slug_chain'));
    }

    public function afterSave($insert, $changedAttributes)
    {
        if (!$insert)
        {
            // Если поменяося родитель, то обновляем slug_chain для все хочерних элементов
            if ($changedAttributes['parent_id'] != $this->parent_id)
            {
                foreach($this->getAllChildren() as $category)
                    $category->updateSlugChain();
            }
        }

        parent::afterSave($insert, $changedAttributes);
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

    public function getLevel()
    {
        return strlen($this->slug_chain) - strlen(str_replace('/','',$this->slug_chain));
    }

    public function getAllChildren()
    {
        $this->categories;
        return self::getListCategories($this->categories);
    }


    /**
     * Получает все категории совсеми дочерними элементами
     *
     * @param null $parentId
     * @param null $queryCallback
     *
     * @return Category[]
     */
    static function findAllWithChildren($parentId = null, $queryCallback = null)
    {
        $query = static::find()->where(['parent_id'=>$parentId])->orderBy(['name'=>SORT_ASC])->indexBy('category_id');

        if ($queryCallback instanceof \Closure)
        {
            call_user_func($queryCallback, $query);
        }

        $categories = $query->all();

        $categoryIds = array_keys($categories);

        if ($categoryIds)
        {
            $children = self::findAllWithChildren($categoryIds);
            $childrenGroupByParent = [];
            foreach($children as $child)
            {
                $childrenGroupByParent[$child->parent_id][] = $child;
            }

            foreach($childrenGroupByParent as $parentId=>$items)
            {
                $categories[$parentId]->populateRelation('categories', $items);
            }
        }

        return $categories;
    }

    /**
     * Получает все дочернии категории единым одномерным смассивом
     *
     * @param null $parentId
     *
     * @return array|Category[]
     */
    static function findAllChildren($parentId = null)
    {
        if (!$parentId)
            return static::find()->all();

        $categories = static::findAllWithChildren($parentId);
        $return = [];
        Category::walkTree($categories, function (Category $category) use (&$return)  {
            $return[$category->category_id] = $category;
        });

        return $return;
    }

    /**
     * Обходит дерево категорий
     *
     * @param $categories Category[]
     * @param $callback - функция обработчик каждого узла дерева
     */
    static function walkTree($categories, $callback)
    {
        $i = 0;
        $count = count($categories);
        foreach($categories as $key=>$category)
        {
            call_user_func($callback, $category, $key, $i, $count );
            if ($category->categories)
                self::walkTree($category->categories, $callback);
            $i++;
        }
    }

    /**
     * Принемает список родительских категорий, для которых получает список всех дочерних и возвращает все одномерным массивом
     *
     * @param $categories
     *
     * @return Category[]
     */
    static function getListCategories($categories)
    {
        $return = [];
        foreach($categories as $category)
        {
            $return[$category->category_id] = $category;

            if ($category->categories)
                $return  = ArrayHelper::merge($return, self::getListCategories($category->categories));
        }

        return $return;
    }
}
