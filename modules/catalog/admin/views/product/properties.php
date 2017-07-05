<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $propertyObject \svsoft\yii\modules\properties\models\data\PropertyObject */
/* @var $this yii\web\View */
/* @var $valueModels \svsoft\yii\modules\properties\models\forms\PropertyValueForm[] */
/* @var $property \svsoft\yii\modules\properties\models\data\Property  */
/* @var $model \svsoft\yii\modules\catalog\models\Product  */

$this->title = 'Редактирование свойств товара: ' . $model->name;

$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->product_id]];
$this->params['breadcrumbs'][] = 'Properties';

?>

<div class="properties-update">


    <?=$this->render('_update_menu', ['model' => $model])?>

    <?=$this->render('@svs-properties/admin/views/actions/_properties_form', ['propertyObject' => $propertyObject, 'groupByProperties' => $groupByProperties])?>

</div>


