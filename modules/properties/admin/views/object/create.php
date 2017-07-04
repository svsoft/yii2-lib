<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model svsoft\yii\modules\properties\models\data\PropertyObject */

$this->title = 'Create Property Object';
$this->params['breadcrumbs'][] = ['label' => 'Property Objects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="property-object-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
