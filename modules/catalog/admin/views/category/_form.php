<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model svsoft\yii\modules\catalog\models\Category */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="catalog-category-form box box-primary">
    <?php $form = ActiveForm::begin([]); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'parent_id')->dropDownList($categories) ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'sort')->textInput(['maxlength' => true]) ?>

        <?=\svsoft\yii\modules\main\files\widgets\UploadFormWidget::widget([
            'model' => $model->getUploadForm(),
            'form'   => $form
        ])?>

        <?= $form->field($model, 'active')->checkbox() ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
