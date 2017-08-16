<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel svsoft\yii\modules\catalog\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $category \svsoft\yii\modules\catalog\models\Category */


if ($category)
{
    foreach($category->getChain() as $parent)
    {
        $this->params['breadcrumbs'][] = ['label' => $parent->name, 'url' => ['category/index', 'parent_id'=>$parent->category_id]];
        $this->title .= $parent->name . '/';
    }
}

$this->title .= 'Товары';
$this->params['breadcrumbs'][] = 'Товары';

?>
<div class="product-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Добавить товар', ['create','category_id'=>$category_id], ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'product_id',
                //'category_id',
                'name',
                'slug',
                'description:ntext',
                'sort',
                // 'images:ntext',
                // 'active',
                // 'created',
                // 'updated',
                // 'price',
                // 'count',
                // 'measure',


                [
                    'class'          => 'yii\grid\ActionColumn',
                    'template'       => '{view}{update}{delete}',
                    'buttonOptions' => ['class'=>'btn btn-info btn-xs button'],
                ],
            ],
        ]); ?>
    </div>
</div>
