<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model svsoft\yii\modules\catalog\models\Category */
/* @var $form yii\widgets\ActiveForm */
/* @var $categories svsoft\yii\modules\catalog\models\Category[] */
?>

<div class="catalog-category-form box box-primary">

    <div class="box-header with-border">
        <? if ($model->category_id):?>
            <?= Html::a('Удалить', ['category/delete-cascade', 'id'=>$model->category_id], ['class' => 'btn btn-danger btn-flat']) ?>
        <? endif;?>
    </div>

    <?php $form = ActiveForm::begin([]); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'parent_id')->dropDownList($categories) ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'slug', [
            'addon' => ['append' => ['content'=>Html::activeCheckbox($model,'slug_lock',['label'=>false])]]
        ])->textInput(['maxlength' => true,'disabled'=>$model->slug_lock ? true : false]) ?>

        <?= $form->field($model, 'sort')->textInput() ?>

        <?=\svsoft\yii\modules\main\files\widgets\UploadFormWidget::widget([
            'model' => $model->getUploadForm(),
            'form'   => $form
        ])?>

        <?= $form->field($model, 'active')->checkbox() ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<?php
$js = <<< JS

    // Биндим обработчики на клик кнопки добавление в корзину в модальном окне
    $('#category-slug_lock').on('change', function (e) {
        var slug_lock = $(this).is(':checked');
        $('#category-slug').attr('disabled',slug_lock);     
    });
JS;

$this->registerJs($js);
?>

<?=$this->render('@views/particles/_modal-product');?>


