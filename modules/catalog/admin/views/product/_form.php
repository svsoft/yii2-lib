<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model svsoft\yii\modules\catalog\models\Product */
/* @var $form yii\widgets\ActiveForm */
/* @var $categories svsoft\yii\modules\catalog\models\Category[] */
?>

<div class="product-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'category_id')->dropDownList($categories) ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

        <?=\svsoft\yii\modules\main\files\widgets\UploadFormWidget::widget([
            'model' => $model->getUploadForm(),
            'form'   => $form
        ])?>

        <?= $form->field($model, 'active')->checkbox() ?>

        <?= $form->field($model, 'sort')->textInput() ?>

        <?= $form->field($model, 'price')->textInput() ?>

        <?= $form->field($model, 'count')->textInput() ?>

        <?= $form->field($model, 'measure')->textInput(['maxlength' => true]) ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
