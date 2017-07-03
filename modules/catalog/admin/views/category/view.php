<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model svsoft\yii\modules\catalog\models\Category */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Catalog Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="catalog-category-view box box-primary">
    <div class="box-header">
        <?= Html::a('Update', ['update', 'id' => $model->category_id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->category_id], [
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
                'category_id',
                'parent_id',
                'name',
                'slug',
                'images:ntext',
                [
                    'attribute' => 'images',
                    'value' => function($model){ return ($html = \svsoft\yii\modules\main\files\FileAttributeHelper::getHtmlImages($model)) ? $html : '-';},
                    'format' => 'html',
                ],
                'active',
            ],
        ]) ?>
    </div>
</div>
