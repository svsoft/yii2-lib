<?php

/* @var $this yii\web\View */
/* @var $model \yii\db\ActiveRecord  */
/* @var $propertyForms \svsoft\yii\modules\properties\models\forms\PropertyForm[] */
/* @var $groups \svsoft\yii\modules\properties\models\data\PropertyGroup[] */

$this->title = 'Редактирование свойств товара: ' . $model->name;

$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->product_id]];
$this->params['breadcrumbs'][] = 'Properties';

?>

<div class="properties-update">

    <?=$this->render('_update_menu', ['model' => $model])?>

    <?=$this->render('@svs-properties/admin/views/actions/_properties_form', ['propertyForms' => $propertyForms, 'groups'=>$groups])?>
</div>

