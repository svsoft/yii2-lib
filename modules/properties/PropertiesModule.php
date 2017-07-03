<?php

namespace svsoft\yii\modules\properties;

use svsoft\yii\modules\main\components\BaseModule;
use yii\filters\AccessControl;

/**
 * TODO: Проверить можно ли писать, несколько значений если свойство не множественное
 * TODO: сделать невозможным сохранять значение свойства с двумя заполненымы полями значений для свойств немножественного значения
 * TODO: добавить индекс в таблицу property_object на поля object_id, model_id, model_type_id
 * TODO: Добавить событие на удаления экземпляра модели в поведении
 * TODO: создать индекс(не уникальный) в таблице property_value ['property_id', 'object_id']
 * TODO: Сделать админку
 * TODO: Добаить в property_object поле group_id
 * TODO: Сделать фильтр
 * TODO: сделать сохранение свойств в транзакции в методе PropertyObject::saveProperties
 * TODO: Добавить комментарии в PropertyObject
 *
 *
 * Properties module definition class
 */
class PropertiesModule extends BaseModule
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'svsoft\yii\modules\properties\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}