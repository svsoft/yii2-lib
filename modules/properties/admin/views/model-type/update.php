<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model svsoft\yii\modules\properties\models\PropertyObjectType */

$this->title = 'Update Property Object Type: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Property Object Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->object_type_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="property-object-type-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
