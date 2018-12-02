<?php

namespace svsoft\yii\modules\properties\traits;

use svsoft\yii\modules\properties\models\data\Property;
use svsoft\yii\modules\properties\models\data\PropertyModelType;
use svsoft\yii\modules\properties\models\data\PropertyObject;
use svsoft\yii\modules\properties\models\data\PropertyValue;
use svsoft\yii\modules\properties\models\forms\PropertyForm;
use svsoft\yii\modules\properties\queries\PropertyObjectQuery;
use yii\base\Exception;
use svsoft\yii\modules\properties\behaviors\PropertiesBehavior;
use yii\db\ActiveQuery;

/**
 * Trait PropertiesTrait
 *
 * @property PropertyObject $propertyObject
 * @property $savePropertiesTogether
 * @property $modelName
 *
 * @method getModelId();
 * @method PropertiesBehavior getPropertiesBehavior()
 *
 * @package svsoft\yii\modules\properties\traits
 */
trait PropertiesTrait
{
    /**
     * @var PropertyObject
     */
    private $propertyObject;

    /**
     * @var PropertyModelType
     */
    static protected $modelType;


    public function getPropertyObjectRelation()
    {
        $modelType = self::getModelType();
        return $this->hasOne(PropertyObject::class, ['model_id' => $this::primaryKey()[0]])
            ->andWhere(['model_type_id' => $modelType->model_type_id])->with('propertyValues')->with('linkedGroups');
    }

    /**
     * @return PropertyObject
     *
     * @throws Exception
     */
    public function getPropertyObject()
    {
        if($this->propertyObject === null)
        {
            $this->propertyObject = $this->propertyObjectRelation;

            if(!$this->propertyObject)
            {
                $modelType = self::getModelType();

                $modelId = $this->getModelId();

                $attributes = ['model_id' => $modelId, 'model_type_id' => $modelType->model_type_id];

                $this->propertyObject = new PropertyObject($attributes);
                // $this->propertyObject
            }
        }

        // Устанавливаем сохранение свойств всместе с моделью
        $this->getPropertiesBehavior()->savePropertiesTogether = true;

        return $this->propertyObject;
    }

    /**
     * Получаем соответствующий тип модели
     *
     * @return PropertyModelType
     * @throws Exception
     */
    static public function getModelType()
    {
        if(!static::$modelType)
            static::$modelType = PropertyModelType::findOne(['class' => __CLASS__]);

        if(!static::$modelType)
        {
            $type = new PropertyModelType();
            $type->name = __CLASS__;
            $type->class = __CLASS__;

            if($type->save())
                static::$modelType = $type;
        }

        if(!static::$modelType)
            throw new Exception(__CLASS__ . ' is not found in PropertyModelType');

        return static::$modelType;
    }

    /**
     * Получает массив свойств привязанны к объекту
     *
     * @return \svsoft\yii\modules\properties\components\ObjectProperty[]
     */
    public function getProperties()
    {
        return $this->getPropertyObject()->getProperties();
    }

    /**
     * Возвращает список свойств ввиде массива
     *
     * @return array
     */
    public function getPropertiesAsArray()
    {
        return $this->getPropertyObject()->getPropertiesAsArray();
    }

    /**
     * @param $slug
     *
     * @return PropertyForm
     */
    public function getPropertyBySlug($slug)
    {
        return $this->getPropertyObject()->getPropertyBySlug($slug);
    }

    /**
     * @param $propertyId
     *
     * @return \svsoft\yii\modules\properties\components\ObjectProperty|null
     */
    public function getPropertyById($propertyId)
    {
        return $this->getPropertyObject()->getPropertyById($propertyId);
    }

    /**
     * Возвращает список ввиде массива- ключ - название свойства, значение - список значений
     *
     * @return array
     */
    public function getPropertyAsList()
    {
        return $this->getPropertyObject()->getPropertyAsList();
    }

    /**
     * @param $properties
     * @param null $query
     *
     * @return ActiveQuery
     */
    static public function filterByProperties($properties, $query = null)
    {
        if(!$properties)
            return $query;

        try
        {
            $modelType = self::getModelType();
        }
        catch(Exception $e)
        {

        }

        if(!$query)
            $query = call_user_func_array([$modelType->class, 'find'], []);

        // Получаем список ид объектов
        $queryPropertyObject = PropertyObject::find()->andProperty($properties);

        $queryPropertyObject->andWhere([PropertyObject::tableName() . '.model_type_id' => $modelType->model_type_id]);

        $objects = $queryPropertyObject->indexBy('model_id')->select('property_object.model_id')->asArray()->all();

        $modelIdColumn = current(self::primaryKey());

        $query->andWhere([$modelIdColumn => array_keys($objects)]);

        return $query;
    }

    /**
     * Возвращает название сущности к которой прикреплены свойства
     *
     *
     * @return string
     * @throws Exception
     */
    function getModelName()
    {
        $attr = $this->getPropertiesBehavior()->nameAttribute;

        if(!$attr)
            throw new Exception('Property nameAttribute is not set in method ' . get_class($this) . '::behaviors() for PropertiesBehavior');

        return $this->getAttribute($attr);
    }

    /**
     * @param null $groups
     */
    function addPropertyGroups($groups = null)
    {
        return $this->getPropertyObject()->addPropertyGroups($groups);
    }
}