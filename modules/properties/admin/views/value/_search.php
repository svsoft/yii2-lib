<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model svsoft\yii\modules\properties\models\PropertyValueSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="property-value-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'value_id') ?>

    <?= $form->field($model, 'property_id') ?>

    <?= $form->field($model, 'object_id') ?>

    <?= $form->field($model, 'string_value') ?>

    <?= $form->field($model, 'text_value') ?>

    <?php // echo $form->field($model, 'int_value') ?>

    <?php // echo $form->field($model, 'float_value') ?>

    <?php // echo $form->field($model, 'timestamp_value') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
