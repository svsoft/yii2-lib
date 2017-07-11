<?php

use unclead\multipleinput\MultipleInput;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $propertyForm \svsoft\yii\modules\properties\models\forms\PropertyForm */

?>


<? if($propertyForm->property->multiple):?>
    <?=$form->field($propertyForm, 'values')->widget(MultipleInput::className(), [
        //'max'               => 100,
        'min'               => 1, // should be at least 2 rows
        'allowEmptyList'    => false,
        'enableGuessTitle'  => false,
        'addButtonPosition' => MultipleInput::POS_FOOTER, // show add button in the header

    ])->label($propertyForm->property->name)?>
<?else:?>
    <?=$form->field($propertyForm, 'values['.$propertyForm->getFirsValueId().'#]')->label($propertyForm->property->name)->textInput()?>
<?endif;?>