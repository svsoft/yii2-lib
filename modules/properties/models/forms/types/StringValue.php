<?php

namespace svsoft\yii\modules\properties\models\forms\types;

use svsoft\yii\modules\properties\models\data\Property;
use svsoft\yii\modules\properties\models\forms\PropertyValueForm;

/**
 * Class StringValue
 *
 * @package svsoft\yii\modules\properties\components\types
 */
class StringValue extends PropertyValueForm
{
    public function getColumnName()
    {
        return Property::columnNameByType(Property::TYPE_STRING);
    }

    function rules()
    {
        $rules = parent::rules(); // TODO: Change the autogenerated stub
        $rules[] = [['value'], 'string', 'max' => 255];


        return $rules;
    }
}