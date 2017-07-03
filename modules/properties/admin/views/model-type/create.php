<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model svsoft\yii\modules\properties\models\PropertyObjectType */

$this->title = 'Create Property Object Type';
$this->params['breadcrumbs'][] = ['label' => 'Property Object Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="property-object-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
