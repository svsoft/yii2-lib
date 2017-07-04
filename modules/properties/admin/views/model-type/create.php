<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model svsoft\yii\modules\properties\models\data\PropertyModelType */

$this->title = 'Create Property Model Type';
$this->params['breadcrumbs'][] = ['label' => 'Property Model Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="property-model-type-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
