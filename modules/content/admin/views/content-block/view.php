<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model svsoft\yii\modules\content\models\ContentBlock */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Content Blocks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-block-view box box-primary">
    <div class="box-header">
        <?= Html::a('Update', ['update', 'id' => $model->block_id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->block_id], [
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
                'block_id',
                'name',
                'key',
                'content:ntext',
                'format',
                'created',
                'updated',
            ],
        ]) ?>
    </div>
</div>
