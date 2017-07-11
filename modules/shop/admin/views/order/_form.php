<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model svsoft\yii\modules\shop\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'user_id')->textInput() ?>

        <?= $form->field($model, 'external_id')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'created')->textInput() ?>

        <?= $form->field($model, 'updated')->textInput() ?>

        <?= $form->field($model, 'status_id')->textInput() ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
