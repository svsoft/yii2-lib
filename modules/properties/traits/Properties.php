<?php

namespace svsoft\yii\modules\properties\traits;

use svsoft\yii\modules\properties\models\data\PropertyModelType;
use svsoft\yii\modules\properties\models\data\PropertyObject;
use yii\base\Exception;

/**
 * Class Properties
 * @property PropertyObject $propertyObject
 * @property $savePropertiesTogether
 * @package svsoft\yii\modules\properties\traits
 */
trait Properties
{

    /**
     * @var PropertyObject
     */
    private $propertyObject;

    /**
     * @var PropertyModelType
     */
    private $modelType;

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
            $this->modelType = PropertyModelType::findOne(['class' => __CLASS__]);

            if (!$this->modelType)
                throw new Exception(__CLASS__ . ' is not found in PropertyModelType');

            $modelId = $this->getModelId();

            $attributes = ['model_id' => $modelId, 'model_type_id' => $this->modelType->model_type_id];

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
}