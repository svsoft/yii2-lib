<?php
/**
 * @var \yii\bootstrap\ActiveForm $form
 * @var \app\modules\content\admin\models\UploadForm $model
 */
?>

<div class="upload-form-widget">
    <?= $form->field($model, 'uploadedFiles[]')->fileInput(['multiple' => true, 'accept' => 'image/*', 'class'=>'bootstrap-file-input','title'=>'Выберите файл']) ?>

    <?
    echo \yii\jui\Sortable::widget([
        'items' => $items,
        'options' => ['tag' => 'div', 'class'=>'upload-form-widget-img-items row'],
        'itemOptions' => ['tag' => 'div', 'class'=>'upload-form-widget-img-item col-lg-2 col-sm-3 col-xs-6'],
        'clientOptions' => ['cursor' => 'move'],
    ]);
    ?>

</div>



<?php
$js = <<< JS
$('.bootstrap-file-input').bootstrapFileInput();
JS;
$this->registerJs($js);
?>