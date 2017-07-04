<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model svsoft\yii\modules\properties\models\data\PropertyObject */

$this->title = 'Update Property Object: ' . $model->object_id;
$this->params['breadcrumbs'][] = ['label' => 'Property Objects', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->object_id, 'url' => ['view', 'id' => $model->object_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="property-object-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
