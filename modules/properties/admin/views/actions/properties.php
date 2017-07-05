<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $propertyObject \svsoft\yii\modules\properties\models\data\PropertyObject */
/* @var $this yii\web\View */
/* @var $valueModels \svsoft\yii\modules\properties\models\forms\PropertyValueForm[] */
/* @var $property \svsoft\yii\modules\properties\models\data\Property  */
/* @var $model \yii\db\ActiveRecord  */

?>

<div class="properties-update">

    <?=$this->render('@svs-properties/admin/views/actions/_properties_form', ['propertyObject' => $propertyObject, 'groupByProperties' => $groupByProperties])?>

</div>


