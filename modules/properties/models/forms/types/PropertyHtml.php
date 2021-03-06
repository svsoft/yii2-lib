<?php

namespace svsoft\yii\modules\properties\models\forms\types;

use svsoft\yii\modules\properties\models\forms\PropertyForm;

/**
 * Class PropertyHtml
 *
 * @package svsoft\yii\modules\properties\components\types
 */
class PropertyHtml extends PropertyForm
{
    function rules()
    {
        $rules = parent::rules(); // TODO: Change the autogenerated stub

        if ($this->property->multiple)
            $rules[] = ['values', 'each', 'rule' => ['string']];
        else
            $rules[] = ['value', 'string'];


        return $rules;
    }
}