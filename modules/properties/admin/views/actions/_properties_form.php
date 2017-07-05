<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $propertyObject \svsoft\yii\modules\properties\models\data\PropertyObject */
/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $valueModels \svsoft\yii\modules\properties\models\forms\PropertyValueForm[] */
/* @var $property \svsoft\yii\modules\properties\models\data\Property  */
?>

<div class="properties-form box box-primary">

    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <? foreach($groupByProperties as $item):?>
            <?
            $printLabel = true;
            $property = $item['property']->property;
            $valueModels = $item['values'];
            ?>
            <? foreach($valueModels as $valueModel):?>

                <?=$form->field($valueModel, 'value')->label($printLabel ?  $item['property']->name : false)->textInput() ?>

                <?$printLabel = false;?>
            <?endforeach;?>
            <? if ($item['property']->property->multiple):?>
                <div class="form-group">
                    <?=Html::a('<i class="fa fa-plus"></i>',['/properties/admin/object/add-value','id'=>$propertyObject->object_id, 'property_id'=>$property->property_id], ['class'=>'add-button btn btn-success btn-flat'])?>
                </div>
            <? endif;?>
        <?endforeach;?>
    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>

<?php
$js = <<< JS
    $('.add-button').on('click', function (e) {
        var href = $(this).attr('href');
        
        var jObj = $(this);
        
        $.get(href).success(function(data) {
            jObj.parent().before($(data).find('#container'));     
        })
        
        return false;
    })
JS;
$this->registerJs($js);
?>