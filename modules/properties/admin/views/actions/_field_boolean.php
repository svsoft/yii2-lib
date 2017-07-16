<?php

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $propertyForm \svsoft\yii\modules\properties\models\forms\PropertyForm */

?>

<?=$form->field($propertyForm, 'value')->label($propertyForm->property->name)->checkbox(['label'=>$propertyForm->property->name])?>
