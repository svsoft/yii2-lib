<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model svsoft\yii\modules\properties\models\data\PropertyValue */

$this->title = $model->value_id;
$this->params['breadcrumbs'][] = ['label' => 'Property Values', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="property-value-view box box-primary">
    <div class="box-header">
        <?= Html::a('Update', ['update', 'id' => $model->value_id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->value_id], [
            'class' => 'btn btn-danger btn-flat',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'value_id',
                'property_id',
                'object_id',
                'string_value',
                'text_value:ntext',
                'int_value',
                'float_value',
                'timestamp_value:datetime',
            ],
        ]) ?>
    </div>
</div>
