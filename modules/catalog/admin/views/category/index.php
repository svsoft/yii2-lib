<?php

use yii\helpers\Html;
use yii\grid\GridView;
use svsoft\yii\modules\catalog\models\Category;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $parentChain Category[] */
/* @var $parent Category */
/* @var $parent_id int */

foreach($parentChain as $category)
{
    $this->params['breadcrumbs'][] = ['label' => $category->name, 'url' => ['category/index', 'parent_id'=>$category->category_id]];
    $this->title .= $category->name . '/';
}

if ($parent)
{
    $this->title .= $parent->name;
    $this->params['breadcrumbs'][] = $parent->name;
}
else
{
    $this->title .= 'Каталог';
    $this->params['breadcrumbs'][] = 'Каталог';
}

?>
<div class="catalog-category-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Добавить категорию', ['create', 'parent_id'=>$parent_id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('Список товаров', ['product/index', 'category_id'=>$parent_id], ['class' => 'btn btn-info btn-flat']) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                [
                    'class' => 'yii\grid\SerialColumn',
                    'contentOptions' => ['class'=>'format-number'],
                    'headerOptions' => ['class'=>'format-number'],
                ],
                [
                    'attribute' => 'category_id',
                    'contentOptions' => ['class'=>'format-number'],
                    'headerOptions' => ['class'=>'format-number'],
                ],

                [
                    'attribute' => 'name',
                    'format'=>'ntext',
                    'contentOptions' => ['class'=>'format-text'],
                    'headerOptions' => ['class'=>'format-text'],
                ],
                [
                    'attribute' => 'slug',
                    'format'=>'ntext',
                    'contentOptions' => ['class'=>'format-text'],
                    'headerOptions' => ['class'=>'format-text'],
                ],
                [
                    'attribute' => 'images',
                    'format'=>'html',
                    'contentOptions' => ['class'=>'format-images'],
                    'headerOptions' => ['class'=>'format-images'],
                    'value'=>
                        function(Category $model) {
                            return Html::img(Yii::$app->image->crop( $model->getFirstFileSrc(),'admin-small'));
                        }
                ],
                [
                    'attribute' => 'sort',
                    'contentOptions' => ['class'=>'format-number'],
                    'headerOptions' => ['class'=>'format-number'],
                ],
                [
                    'class'=>\svsoft\yii\modules\catalog\admin\components\StatusColumn::className(),
                    'attribute' => 'active',
                ],
                [
                    'class'          => 'yii\grid\ActionColumn',
                    'template'       => '{children}{products}<span class="button-separator"></span>{view}{update}{delete}',
                    'visibleButtons' => [
//                        'children' => function(Category $model){ return $model->categories; },
//                        'products' => function(Category $model){ return $model->products; },
                    ],
                    'buttonOptions' => ['class'=>'btn btn-info btn-xs button'],
                    'contentOptions' => ['class'=>'format-action'],
                    'headerOptions' => ['class'=>'format-action'],
                    'buttons'        => [
                        'children' => function ($url, $model)
                        {
                            $disabled = $model->categories ? '' : 'disabled';

                            return Html::a(Html::tag('i','',['class'=>'fa fa-bars']), ['category/index', 'parent_id'=>$model->category_id], ['class' => 'btn btn-info btn-xs button '.$disabled]);
                        },
                        'products' => function ($url, Category $model, $key)
                        {
                            $disabled = $model->products ? '' : 'disabled';

                            return Html::a(Html::tag('i','',['class'=>'fa fa-shopping-cart']), ['product/index?category_id='.$model->category_id], ['class' => 'btn btn-info btn-xs button '. $disabled]);
                        },
                    ]
                ],
            ],
        ]); ?>
    </div>
</div>
