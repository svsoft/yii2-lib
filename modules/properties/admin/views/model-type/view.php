<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model svsoft\yii\modules\properties\models\PropertyObjectType */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Property Object Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="property-object-type-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->object_type_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->object_type_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'object_type_id',
            'name',
            'slug',
        ],
    ]) ?>

</div>
