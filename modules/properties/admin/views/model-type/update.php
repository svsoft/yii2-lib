<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model svsoft\yii\modules\properties\models\data\PropertyModelType */

$this->title = 'Update Property Model Type: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Property Model Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->model_type_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="property-model-type-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
