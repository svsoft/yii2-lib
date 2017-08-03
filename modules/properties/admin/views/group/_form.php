<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use svsoft\yii\modules\properties\components\PropertyHelper;

/* @var $this yii\web\View */
/* @var $model svsoft\yii\modules\properties\models\data\PropertyGroup */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="property-group-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'model_type_id')->dropDownList(PropertyHelper::getModelTypeList('-Выберите тип объекта-')) ?>

        <?= $form->field($model, 'require')->checkbox() ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
