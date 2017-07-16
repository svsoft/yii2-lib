<?php

namespace svsoft\yii\modules\properties\models\forms\types;

use svsoft\yii\modules\properties\models\forms\PropertyForm;

/**
 * Class PropertyBoolean
 *
 * @package svsoft\yii\modules\properties\components\types
 */
class PropertyBoolean extends PropertyForm
{
    function rules()
    {
        $rules = parent::rules(); // TODO: Change the autogenerated stub
        $rules[] = ['value', 'boolean', 'trueValue' => 1, 'falseValue' => 0];

        return $rules;
    }
}