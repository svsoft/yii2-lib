<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use svsoft\yii\modules\properties\models\data\Property;


/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $propertyForms \svsoft\yii\modules\properties\models\forms\PropertyForm[] */
/* @var $groups \svsoft\yii\modules\properties\models\data\PropertyGroup[] */

?>

<div class="properties-form box box-primary">

    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive row ">
        <div class="">

            <? foreach($groups as $group):?>
                <div class="col-md-6">
                    <div class="box box-solid box-primary">

                        <div class="box-header">
                            <h3 class="box-title"><?=Html::encode($group->name)?></h3>
                        </div>

                        <div class="box-body">
                            <? foreach($group->properties as $property):?>
                                <?$propertyForm = $propertyForms[$property->property_id]?>
                                <?=$this->render('_field', ['form' => $form, 'propertyForm' => $propertyForm])?>
                            <?endforeach;?>
                        </div>
                    </div>
                </div>
            <?endforeach;?>
        </div>
    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<div class="">

</div>
