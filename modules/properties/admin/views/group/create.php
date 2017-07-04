<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model svsoft\yii\modules\properties\models\data\PropertyGroup */

$this->title = 'Create Property Group';
$this->params['breadcrumbs'][] = ['label' => 'Property Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="property-group-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
