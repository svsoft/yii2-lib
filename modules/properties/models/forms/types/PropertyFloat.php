<?php

namespace svsoft\yii\modules\properties\models\forms\types;

use svsoft\yii\modules\properties\models\forms\PropertyForm;

/**
 * Class PropertyFloat
 *
 * @package svsoft\yii\modules\properties\components\types
 */
class PropertyFloat extends PropertyForm
{
    function rules()
    {
        $rules = parent::rules(); // TODO: Change the autogenerated stub

        // Для множественного
        $rules[] = ['values', 'each', 'rule' => ['number']];
        // Для немножественного
        $rules[] = ['value', 'number'];

        return $rules;
    }
}