<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use svsoft\yii\modules\content\components\ContentHelper;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model svsoft\yii\modules\content\models\Document */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin(['enableClientValidation' => false]); ?>

<div class="row document-form">

    <div class="col-md-6">
        <div class=" box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Основное</h3>
            </div>
            <div class="box-body">

                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'parent_id')->dropDownList(ContentHelper::getParentList(false, $model->document_id)) ?>

                <?= $form->field($model, 'children')->checkbox() ?>

                <?= $form->field($model, 'active')->checkbox() ?>

                <?= $form->field($model, 'sort')->textInput() ?>

                <div class="row">
                    <div class="col-md-6">
                        <?=$form->field($model, 'created')->widget(\kartik\datecontrol\DateControl::classname(), [
                            'type'=>\kartik\datecontrol\DateControl::FORMAT_DATETIME,
                        ]);?>
                    </div>
                    <div class="col-md-6">
                        <?=$form->field($model, 'updated')->textInput( ['disabled'=>true, 'value'=>$model->updatedFormatted] )?>
                        <?//= $form->field($model, 'updated')->widget(DatePicker::className(),['clientOptions' => [], 'options'=>['class'=>'form-control']]) ?>
                        <?//= $form->field($model, 'updatedFormatted')->widget(DatePicker::className(),['clientOptions' => [], 'options'=>['class'=>'form-control']]) ?>
                    </div>
                </div>

            </div>


        </div>
    </div><!--col-md-6-->
    <div class="col-md-6">
        <div class=" box box-primary">
            <div class="box-body table-responsive">
                <div class="box-header with-border">
                    <h3 class="box-title">SEO</h3>
                </div>

                <?= $form->field($model, 'slug')->hint($model->slug_chain)->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'h1')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'description')->textarea(['rows' => 2]) ?>

            </div>

        </div>
    </div><!--col-md-6-->

    <div class="col-md-12">
        <div class=" box box-primary">
            <div class="box-body table-responsive">
                <div class="box-header with-border">
                    <h3 class="box-title">Контент</h3>
                </div>

                <?= $form->field($model, 'content')->widget(CKEditor::className(),[
                    'options' => ['rows' => 6],
                    'preset' => 'full',
                    'clientOptions'=>ElFinder::ckeditorOptions(['elfinder']) + [
                            'allowedContent' => 'a pre blockquote img em p i h1 h2 h3 div span table tbody thead tr th td ul li ol(*)[*]; br hr strong;',
                            'height'=>250
                        ]
                ]); ?>

                <?= $form->field($model, 'preview')->widget(CKEditor::className(),[
                    'options' => ['rows' => 6],
                    'preset' => 'full',
                    'clientOptions'=>ElFinder::ckeditorOptions(['elfinder']) + [
                            'allowedContent' => 'a pre blockquote img em p i h1 h2 h3 div span table tbody thead tr th td ul li ol(*)[*]; br hr strong;',
                            'height'=>100
                        ]
                ]); ?>

                <?=\svsoft\yii\modules\main\files\widgets\UploadFormWidget::widget([
                    'model' => $model->getUploadForm(),
                    'form'   => $form
                ])?>

            </div>
            <div class="box-footer">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
            </div>
        </div>
    </div><!--col-md-12-->
</div>

<?php ActiveForm::end(); ?>


