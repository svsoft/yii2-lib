<?php
/**
 * Created by PhpStorm.
 * User: viktor
 * Date: 13.06.2017
 * Time: 23:17
 */

namespace svsoft\yii\modules\properties\components\types;

use svsoft\yii\modules\properties\components\ObjectPropertyValue;


/**
 * Class Value
 *
 * @package svsoft\yii\modules\properties\models
 */
class FloatValue extends ObjectPropertyValue
{
    public function getColumnName()
    {
        return 'float_value';
    }

    public function displayValue()
    {
        return (float)$this->value;
    }
}