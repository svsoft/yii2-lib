<?php

use svsoft\yii\modules\properties\models\data\Property;


/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $propertyForm \svsoft\yii\modules\properties\models\forms\PropertyForm */


switch($propertyForm->property->type)
{
    case Property::TYPE_STRING:
        echo $this->render('_field_string', ['form' => $form, 'propertyForm' => $propertyForm]);
        break;
    case Property::TYPE_TEXT:
        echo $this->render('_field_text', ['form' => $form, 'propertyForm' => $propertyForm]);
        break;
    case Property::TYPE_INTEGER:
        echo $this->render('_field_integer', ['form' => $form, 'propertyForm' => $propertyForm]);
        break;
    case Property::TYPE_FLOAT:
        echo $this->render('_field_float', ['form' => $form, 'propertyForm' => $propertyForm]);
        break;
    case Property::TYPE_TIMESTAMP:
        echo $this->render('_field_timestamp', ['form' => $form, 'propertyForm' => $propertyForm]);
        break;
    case Property::TYPE_BOOLEAN:
        echo $this->render('_field_boolean', ['form' => $form, 'propertyForm' => $propertyForm]);
        break;
    case Property::TYPE_HTML:
        echo $this->render('_field_html', ['form' => $form, 'propertyForm' => $propertyForm]);
        break;

    default;
}