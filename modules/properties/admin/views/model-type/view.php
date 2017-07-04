<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model svsoft\yii\modules\properties\models\data\PropertyModelType */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Property Model Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="property-model-type-view box box-primary">
    <div class="box-header">
        <?= Html::a('Update', ['update', 'id' => $model->model_type_id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->model_type_id], [
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
                'model_type_id',
                'name',
                'slug',
                'class',
            ],
        ]) ?>
    </div>
</div>
