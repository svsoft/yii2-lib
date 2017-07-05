<?php
/**
 * Created by PhpStorm.
 * User: viktor
 * Date: 13.06.2017
 * Time: 23:17
 */

namespace svsoft\yii\modules\properties\components\types;

use svsoft\yii\modules\properties\components\ObjectPropertyValue;
use svsoft\yii\modules\properties\models\data\Property;
use yii\helpers\ArrayHelper;

/**
 * Class BooleanValue
 *
 * @package svsoft\yii\modules\properties\models
 */
class BooleanValue extends ObjectPropertyValue
{
    public function getColumnName()
    {
        return Property::columnNameByType(Property::TYPE_BOOLEAN);
    }

    public function displayValue($values = ['Да','Нет'])
    {
        return ArrayHelper::getValue($values, $this->value, '');
    }
}