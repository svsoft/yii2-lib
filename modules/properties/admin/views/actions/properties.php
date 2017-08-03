<?php

/* @var $this yii\web\View */
/* @var $model \yii\db\ActiveRecord  */
/* @var $propertyForms \svsoft\yii\modules\properties\models\forms\PropertyForm[] */
/* @var $groups \svsoft\yii\modules\properties\models\data\PropertyGroup[] */
/* @var $object \svsoft\yii\modules\properties\models\data\PropertyObject */

?>

<div class="properties-update">

    <?=$this->render('@svs-properties/admin/views/actions/_properties_form', ['propertyForms' => $propertyForms, 'groups'=>$groups, 'object'=>$object])?>

</div>
