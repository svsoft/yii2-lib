<?php

namespace svsoft\yii\modules\properties\admin\actions;

use svsoft\yii\modules\catalog\models\Category;
use svsoft\yii\modules\properties\models\data\Property;
use svsoft\yii\modules\properties\models\data\PropertyModelType;
use svsoft\yii\modules\properties\models\data\PropertyObject;
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
        parent::init(); // TODO: Change the autogenerated stub

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

        $object = PropertyObject::findOneElseInsert($modelType->model_type_id, $id);

        $properties = $object->properties;

        foreach($properties as $property)
        {
            $propertyId = $property->property->property_id;

            $groupByProperties[$propertyId]['property'] = $property;

            $valueModel = PropertyValueForm::createForm($property->property);

            if ($property->isEmpty())
            {
                $valueModels[] = $valueModel;
                $valueModel->createPropertyValue($object);

                $groupByProperties[$propertyId]['values'][] = $valueModel;
            }
            else
            {
                foreach($property->getValues() as $value)
                {
                    $valueModelClone = clone $valueModel;
                    $valueModelClone->setPropertyValue($value->getPropertyValue());
                    $valueModels[] = $valueModelClone;

                    $groupByProperties[$propertyId]['values'][] = $valueModelClone;
                }
            }
        }

        if (Yii::$app->request->isPost)
        {
            $valid = true;
            foreach($valueModels as $valueModel)
            {
                $valueModel->load(Yii::$app->request->post());
                if (!$valueModel->validate())
                    $valid = false;

                if (!$valid)
                {
                    var_dump($valueModel->errors);
                }
            }

            if ($valid)
            {
                foreach($valueModels as $valueModel)
                {
                    if (!$valueModel->save())
                        $valid = false;
                }

                if ($valid)
                    $this->controller->refresh();
            }
        }


        return $this->controller->render($this->view, [
            'propertyObject' => $object,
            'groupByProperties' => $groupByProperties,
            'model' => $model,
        ]);

    }
}
