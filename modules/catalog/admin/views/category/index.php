<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Catalog Categories';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="catalog-category-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Create Catalog Category', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'category_id',
                'parent_id',
                'name',
                'slug',
                'images:ntext',
                // 'active',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>
