<?php

namespace svsoft\yii\modules\properties\models\forms\types;

use svsoft\yii\modules\properties\models\forms\PropertyValueForm;

/**
 * Class FloatValue
 *
 * @package svsoft\yii\modules\properties\components\types
 */
class FloatValue extends PropertyValueForm
{
    public function getColumnName()
    {
        return 'float_value';
    }

    function rules()
    {
        $rules = parent::rules();

        $rules[] = [['value'], 'number'];

        return $rules;
    }
}