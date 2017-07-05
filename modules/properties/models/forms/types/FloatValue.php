<?php

namespace svsoft\yii\modules\properties\models\forms\types;

use svsoft\yii\modules\properties\models\data\Property;
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
        return Property::columnNameByType(Property::TYPE_FLOAT);
    }

    function rules()
    {
        $rules = parent::rules();

        $rules[] = [['value'], 'number'];

        return $rules;
    }
}