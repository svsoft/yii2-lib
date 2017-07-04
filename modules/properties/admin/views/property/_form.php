<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model svsoft\yii\modules\properties\models\data\Property */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="property-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'model_type_id')->textInput() ?>

        <?= $form->field($model, 'group_id')->textInput() ?>

        <?= $form->field($model, 'type')->textInput() ?>

        <?= $form->field($model, 'multiple')->textInput() ?>

        <?= $form->field($model, 'active')->textInput() ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
