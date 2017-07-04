<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>


<?php $form = ActiveForm::begin(); ?>
<div id="container">
    <?=$form->field($valueModel, 'value')->label(false)->textInput() ?>
</div>
<?php ActiveForm::end(); ?>