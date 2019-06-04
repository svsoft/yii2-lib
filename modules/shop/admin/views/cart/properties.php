<?php

/* @var $this yii\web\View */
/* @var $model \svsoft\yii\modules\shop\models\Order  */
/* @var $propertyForms \svsoft\yii\modules\properties\models\forms\PropertyForm[] */
/* @var $groups \svsoft\yii\modules\properties\models\data\PropertyGroup[] */

$this->title = 'Редактирование свойств элемента корхины: ' . $model->order_id;

$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->order_id, 'url' => ['view', 'id' => $model->order_id]];
$this->params['breadcrumbs'][] = 'Properties';

?>

<div class="properties-update">

    <?=$this->render('_update_menu', ['model' => $model])?>

    <?=$this->render('@svs-properties/admin/views/actions/_properties_form', ['propertyForms' => $propertyForms,'object'=>$object])?>

</div>

