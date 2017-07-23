<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;

/* @var $this yii\web\View */
/* @var $model svsoft\yii\modules\content\models\Document */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="document-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'parent_id')->textInput() ?>

        <?= $form->field($model, 'children')->textInput() ?>

        <?= $form->field($model, 'content')->widget(CKEditor::className(),[
            'options' => ['rows' => 6],
            'preset' => 'full',
            'clientOptions'=>ElFinder::ckeditorOptions(['elfinder']) + [
                    'allowedContent' => 'a pre blockquote img em p i h1 h2 h3 div span table tbody thead tr th td ul li ol(*)[*]; br hr strong;',
                ]
        ]); ?>

        <?= $form->field($model, 'preview')->widget(CKEditor::className(),[
            'options' => ['rows' => 6],
            'preset' => 'full',
            'clientOptions'=>ElFinder::ckeditorOptions(['elfinder']) + [
                    'allowedContent' => 'a pre blockquote img em p i h1 h2 h3 div span table tbody thead tr th td ul li ol(*)[*]; br hr strong;',
                ]
        ]); ?>

        <?= $form->field($model, 'active')->checkbox() ?>

        <?= $form->field($model, 'created')->textInput() ?>

        <?= $form->field($model, 'updated')->textInput() ?>

        <?= $form->field($model, 'sort')->textInput() ?>

        <?=\svsoft\yii\modules\main\files\widgets\UploadFormWidget::widget([
            'model' => $model->getUploadForm(),
            'form'   => $form
        ])?>

        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'h1')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
