<?php

namespace svsoft\yii\modules\properties\traits;

use svsoft\yii\modules\properties\models\data\Property;
use svsoft\yii\modules\properties\models\data\PropertyModelType;
use svsoft\yii\modules\properties\models\data\PropertyObject;
use svsoft\yii\modules\properties\models\data\PropertyValue;
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
    static private $modelType;

    /**
     * флаг сохранения свойств вместе с моделью
     *
     * @var bool
     */
    private $savePropertiesTogether = false;


    /**
     * @return PropertyObject
     *
     * @throws Exception
     */
    public function getPropertyObject()
    {
        if ($this->propertyObject === null)
        {
            $modelType = self::getModelType();

            if (!$modelType)
                throw new Exception(__CLASS__ . ' is not found in PropertyModelType');

            $modelId = $this->getModelId();

            $attributes = ['model_id' => $modelId, 'model_type_id' => $modelType->model_type_id];

            $this->propertyObject = PropertyObject::findOne($attributes);

            if(!$this->propertyObject)
            {
                $this->propertyObject = new PropertyObject($attributes);
            }

            // Устанавливаем сохранение свойств всместе с моделью
            $this->savePropertiesTogether = true;
        }


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
        if (!static::$modelType)
            static::$modelType = PropertyModelType::findOne(['class' => __CLASS__]);

        if (!static::$modelType)
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
     * @return \svsoft\yii\modules\properties\components\ObjectProperty|null
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
     * геттер для self::savePropertiesTogether
     *
     * @return mixed
     */
    public function getSavePropertiesTogether()
    {
        return $this->savePropertiesTogether;
    }

    /**
     * @param $properties
     * @param null $query
     *
     * @return ActiveQuery
     */
    static public function filterByProperties($properties, $query = null)
    {
        if (!$properties)
            return $query;

        $modelType = self::getModelType();

        if (!$query)
            $query = call_user_func_array([$modelType->class,'find'],[]);

        // Получаем список ид объектов
        $queryPropertyObject = PropertyObject::find()->andProperty($properties);

        $queryPropertyObject->andWhere([PropertyObject::tableName() . '.model_type_id'=>$modelType->model_type_id]);

        $objects = $queryPropertyObject->indexBy('model_id')->select('property_object.model_id')->asArray()->all();

        $modelIdColumn = current(self::primaryKey());

        $query->andWhere([$modelIdColumn=>array_keys($objects)]);

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

        if (!$attr)
            throw new Exception('Property nameAttribute is not set in method '.get_class($this).'::behaviors() for PropertiesBehavior');

        return $this->getAttribute($attr);
    }




}