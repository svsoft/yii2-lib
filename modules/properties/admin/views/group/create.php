<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model svsoft\yii\modules\properties\models\PropertyGroup */

$this->title = 'Create Property Group';
$this->params['breadcrumbs'][] = ['label' => 'Property Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="property-group-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
