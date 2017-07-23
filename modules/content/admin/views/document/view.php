<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model svsoft\yii\modules\content\models\Document */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Documents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="document-view box box-primary">
    <div class="box-header">
        <?= Html::a('Update', ['update', 'id' => $model->document_id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->document_id], [
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
                'document_id',
                'parent_id',
                'name',
                'slug',
                'children',
                'content:ntext',
                'preview:ntext',
                'active',
                'created',
                'updated',
                'sort',
                'images:ntext',
                'title',
                'h1',
                'description:ntext',
            ],
        ]) ?>
    </div>
</div>
