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
 * Class IntegerValue
 *
 * @package svsoft\yii\modules\properties\components\types
 */
class StringValue extends ObjectPropertyValue
{
    public function getColumnName()
    {
        return 'string_value';
    }

    public function displayValue()
    {
        return htmlspecialchars($this->value);
    }
}