<?php

namespace svsoft\yii\modules\properties\admin\actions;

use svsoft\yii\modules\catalog\models\Category;
use svsoft\yii\modules\properties\models\data\Property;
use svsoft\yii\modules\properties\models\data\PropertyGroup;
use svsoft\yii\modules\properties\models\data\PropertyModelType;
use svsoft\yii\modules\properties\models\data\PropertyObject;
use svsoft\yii\modules\properties\models\forms\PropertyForm;
use svsoft\yii\modules\properties\models\forms\PropertyValueForm;
use yii\base\Action;
use Yii;
use yii\base\Exception;
use yii\base\Model;

/**
 * Default controller for the `admin2` module
 */
class PropertiesAction extends Action
{

    public $view;

    /**
     * Класс модели к которой прикреалены свойства. по нему определяется тип модели из таблицы property_model_type
     *
     * @var
     */
    public $modelClass;

    /**
     * Renders the index view for the module
     *
     * @return string
     */
    public function init()
    {
        parent::init();

        if (!$this->modelClass)
            throw new Exception('Property "modelClass" is not set');

        if (!$this->view)
            $this->view = '@svs-properties/admin/views/actions/properties';
    }

    public function run($id)
    {
        // Получаем тип модели по классу
        $modelType = PropertyModelType::findOne(['class'=>$this->modelClass]);
        if (!$modelType)
            throw new Exception('PropertyModelType is not found by class "' . $this->modelClass . '""');

        // Получаем модель
        $model = call_user_func_array([$this->modelClass, 'findOne'], [$id]);
        if (!$model)
            throw new Exception('Model '.$this->modelClass.' is not found by id "' . $id . '""');

        // Получаем объект связи модели и свойств
        $object = PropertyObject::findOneElseInsert($modelType->model_type_id, $id);

        $groups = $object->getGroupsWithProperties();

        /**
         * @var $propertyForms PropertyForm[]
         */
        $propertyForms = [];
        foreach($groups as $group)
        {
            foreach($group->activeProperties as $property)
            {
                $propertyForm = PropertyForm::createForm($object, $property);
                $propertyForms[$property->property_id] = $propertyForm;
            }
        }

        if (Yii::$app->request->isPost)
        {
            $valid = true;
            foreach($propertyForms as $propertyForm)
            {
                if ($propertyForm->load(Yii::$app->request->post()))
                {
                    if (!$propertyForm->save())
                    {
                        $valid = false;
                    }
                }
            }

            if ($valid)
                $this->controller->refresh();
        }

        // $this->view = '@svs-properties/admin/views/actions/properties';
        return $this->controller->render($this->view, [
            'propertyForms'=>$propertyForms,
            'model' => $model,
            'object' => $object,
            'groups' => $groups,
        ]);
    }

}
