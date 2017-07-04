<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model svsoft\yii\modules\properties\models\data\PropertyValue */

$this->title = 'Create Property Value';
$this->params['breadcrumbs'][] = ['label' => 'Property Values', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="property-value-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
