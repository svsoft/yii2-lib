<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model svsoft\yii\modules\properties\models\data\PropertyValue */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="property-value-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'property_id')->textInput() ?>

        <?= $form->field($model, 'object_id')->textInput() ?>

        <?= $form->field($model, 'string_value')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'text_value')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'int_value')->textInput() ?>

        <?= $form->field($model, 'float_value')->textInput() ?>

        <?= $form->field($model, 'timestamp_value')->textInput() ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
