<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use svsoft\yii\modules\content\models\ContentBlock;

/* @var $this yii\web\View */
/* @var $model svsoft\yii\modules\content\models\ContentBlock */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="content-block-form box box-primary">
    <?php $form = ActiveForm::begin(['id'=>'content-block-form','enableClientValidation' => false]); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>




        <div class="nav-tabs-custom">

            <?= $form->field($model, 'format')->hiddenInput() ?>

            <ul class="nav nav-tabs">
                <li class="<?if($model->format == ContentBlock::FORMAT_TEXT) echo 'active'?>">
                    <a href="#" data-format="<?=ContentBlock::FORMAT_TEXT?>">Текст</a>
                </li>
                <li class="<?if($model->format == ContentBlock::FORMAT_HTML) echo 'active'?>">
                    <a href="#" data-format="<?=ContentBlock::FORMAT_HTML?>">Html</a>
                </li>
            </ul>

            <div class="tab-content">
                <? if ($model->format == ContentBlock::FORMAT_TEXT ):?>
                    <?= $form->field($model, 'content')->textarea(['rows' => 6])->label('') ?>
                <?else:?>
                    <?= $form->field($model, 'content')->widget(CKEditor::className(),[
                        'options' => ['rows' => 6],
                        'preset' => 'full',
                        'clientOptions'=>ElFinder::ckeditorOptions(['elfinder']) + [
                                'allowedContent' => 'a pre blockquote img em p i h1 h2 h3 div span table tbody thead tr th td ul li ol(*)[*]; br hr strong;',
                                'height'=>150
                            ]
                    ])->label(''); ?>
                <? endif;?>
            </div>
            <!-- /.tab-content -->
        </div>

    </div>
    <div class="box-footer">
        <?= Html::hiddenInput('refresh',0)?>
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<?php
$js = <<< JS

    var jqForm = $('#content-block-form');
    
    // Биндим обработчики на клик кнопки добавление в корзину в модальном окне
    $('.nav-tabs').on('click', 'a', function (e) {
                
        var format = $(this).attr('data-format');
        
        $('#contentblock-format').val(format);
        
        jqForm.find('input[name=refresh]').val(1);
        
        jqForm.submit();
        
        return false;
    });

JS;

$this->registerJs($js);
?>
