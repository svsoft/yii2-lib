<?php

use unclead\multipleinput\MultipleInput;
use dosamigos\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $propertyForm \svsoft\yii\modules\properties\models\forms\PropertyForm */

?>



<? if($propertyForm->property->multiple):?>
<?else:?>
    <?= $form->field($propertyForm, 'value')->widget(CKEditor::className(),[
        'options' => ['rows' => 6],
        'preset' => 'full',
        'clientOptions'=>ElFinder::ckeditorOptions(['elfinder']) + [
                'allowedContent' => 'a pre blockquote img em p i h1 h2 h3 div span table tbody thead tr th td ul li ol(*)[*]; br hr strong;',
                'height'=>250
            ]
    ])->label($propertyForm->property->name) ?>
<?endif;?>