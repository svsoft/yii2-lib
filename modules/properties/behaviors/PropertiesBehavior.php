<?php

namespace svsoft\yii\modules\properties\behaviors;

use svsoft\yii\modules\properties\traits\Properties;
use svsoft\yii\modules\properties\traits\PropertiesTrait;
use yii\base\Event;
use yii\base\Exception;
use yii\base\Behavior;
use yii\base\ModelEvent;
use yii\db\ActiveRecord;

/**
 * TODO: Добавить событие после удаления
 *
 * Class Tree
 * @package app\behaviors
 *
 * @property PropertiesTrait $owner
 */
class PropertiesBehavior extends Behavior
{
    /**
     * @var \Closure
     */
    public $getId;


    /**
     * Название атрибуто которое будет использоваться в качестви имени модели
     *
     * @var str
     */
    public $nameAttribute;

    public function init()
    {
        if (empty($this->getId))
            throw new Exception('Property getId does not set in ' . __CLASS__);

        parent::init();
    }

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'afterInsert',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
            ActiveRecord::EVENT_AFTER_VALIDATE => 'afterValidate',
            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete'
        ];
    }

    /**
     * Получает ид модели для привязки к свойствам
     *
     * @return mixed
     */
    public function getModelId()
    {
        return call_user_func($this->getId, $this->owner);
    }

    /**
     * После валидации модели валидируем примязанные свойства
     *
     * @param ModelEvent $event
     */
    public function afterValidate()
    {
        if (!$this->owner->savePropertiesTogether)
            return;

        $propertyObject = $this->owner->propertyObject;

        if (!$propertyObject->validateProperties())
            $this->owner->addError('properties', 'Properties validation error');
    }

    public function afterInsert()
    {
        // Проверяем надо ли сохранять свойства
        if ($this->owner->savePropertiesTogether)
        {
            // Проставляем в propertyObject ид вставленой в БД модели и сохраняем содкль
            $propertyObject = $this->owner->getPropertyObject();
            $propertyObject->model_id = $this->getModelId();

            $propertyObject->save();
        }

        $this->afterSave();
    }

    public function afterSave()
    {
        // Проверяем надо ли сохранять свойства
        if ($this->owner->savePropertiesTogether)
            $this->owner->getPropertyObject()->saveProperties();
    }

    public function beforeDelete()
    {
        $this->owner->getPropertyObject()->delete();
    }

    public function getPropertiesBehavior()
    {
        return $this;
    }

}
