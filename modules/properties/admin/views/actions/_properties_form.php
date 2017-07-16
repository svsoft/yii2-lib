<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use svsoft\yii\modules\properties\models\data\Property;


/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $propertyForms \svsoft\yii\modules\properties\models\forms\PropertyForm[] */
?>

<div class="properties-form box box-primary">

    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <? foreach($propertyForms as $propertyForm):?>
            <?=$this->render('_field', ['form' => $form, 'propertyForm' => $propertyForm])?>
        <?endforeach;?>
    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>