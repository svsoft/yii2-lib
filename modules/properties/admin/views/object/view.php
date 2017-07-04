<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model svsoft\yii\modules\properties\models\data\PropertyObject */

$this->title = $model->object_id;
$this->params['breadcrumbs'][] = ['label' => 'Property Objects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="property-object-view box box-primary">
    <div class="box-header">
        <?= Html::a('Update', ['update', 'id' => $model->object_id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->object_id], [
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
                'object_id',
                'model_id',
                'model_type_id',
            ],
        ]) ?>
    </div>
</div>
