<?php

/* @var $this yii\web\View */
/* @var $model \yii\db\ActiveRecord  */
/* @var $propertyForms \svsoft\yii\modules\properties\models\forms\PropertyForm[] */

?>

<div class="properties-update">

    <?=$this->render('@svs-properties/admin/views/actions/_properties_form', ['propertyForms' => $propertyForms])?>

</div>
