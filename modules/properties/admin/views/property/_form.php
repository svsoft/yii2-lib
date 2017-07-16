<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use svsoft\yii\modules\properties\components\PropertyHelper;

/* @var $this yii\web\View */
/* @var $model svsoft\yii\modules\properties\models\data\Property */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="property-form box box-primary">
    <?php $pjax = \yii\widgets\Pjax::begin(['formSelector' => '#property-form']);?>

    <?php $form = ActiveForm::begin(['id'=>'property-form', 'enableClientValidation' => false]); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'model_type_id')->dropDownList(PropertyHelper::getModelTypeList('-Выберите тип объекта-')) ?>

        <?= $form->field($model, 'group_id')->dropDownList(PropertyHelper::getGroupList($model->model_type_id, '-Без группы-'), ['disabled'=>!$model->model_type_id]) ?>

        <?= $form->field($model, 'type')->dropDownList(PropertyHelper::getTypeList('-Выберите тип-')) ?>

        <?= $form->field($model, 'multiple')->checkbox() ?>

        <?= $form->field($model, 'active')->checkbox() ?>

        <?=Html::hiddenInput('mode');?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>

    <?php \yii\widgets\Pjax::end()?>

</div>


<?php
$js = <<< JS

    $(document).on('change', '#property-model_type_id', function(e) {
        $('#property-form input[name=mode]').val('set-model-type');
        $('#property-form').submit();
    })
JS;
$this->registerJs($js);
?>
