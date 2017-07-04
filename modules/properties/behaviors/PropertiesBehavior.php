<?php

namespace svsoft\yii\modules\properties\behaviors;

use svsoft\yii\modules\properties\traits\Properties;
use yii\base\Exception;
use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 * Class Tree
 * @package app\behaviors
 *
 * @property \yii\db\ActiveRecord|Properties $owner
 */
class PropertiesBehavior extends Behavior
{
    /**
     * @var \Closure
     */
    public $getId;

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

}
