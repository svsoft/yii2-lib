<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model svsoft\yii\modules\properties\models\data\PropertyGroup */

$this->title = 'Update Property Group: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Property Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->group_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="property-group-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
