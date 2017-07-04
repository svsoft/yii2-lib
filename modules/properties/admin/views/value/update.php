<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model svsoft\yii\modules\properties\models\data\PropertyValue */

$this->title = 'Update Property Value: ' . $model->value_id;
$this->params['breadcrumbs'][] = ['label' => 'Property Values', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->value_id, 'url' => ['view', 'id' => $model->value_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="property-value-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
