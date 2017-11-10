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
                    'class'=>\svsoft\yii\modules\admin\components\StatusColumn::className(),
                    'attribute' => 'active',
                ],
                [
                    'class'          => \svsoft\yii\modules\admin\components\ActionColumn::className(),
                    'template'       => '<div class="btn-group btn-sm-group-2">{children}{products}</div><div class="btn-group btn-sm-group-2">{update}{delete}</div>',
                    'buttonOptions' => ['class'=>'btn btn-info btn-sm'],
                    'contentOptions' => ['class'=>'format-action'],
                    'headerOptions' => ['class'=>'format-action'],
                    'buttons'        => [
                        'children' => [

                            'url'=> function(Category $model) { return \yii\helpers\Url::to(['category/index', 'parent_id'=>$model->category_id]); },
                            'content'=>'<i class="fa fa-bars"></i>',
                            'class'=>'btn btn-sm btn-default',
                            'appendOptions'=>[
                                'class'=>function($model) { return !$model->categories ? 'disabled' : ''; }
                            ]
                        ],
                        'products' => [
                            'url'=> function(Category $model) { return \yii\helpers\Url::to(['product/index?category_id='.$model->category_id]); },
                            'content'=>'<i class="fa fa-shopping-cart"></i>',
                            'class'=>'btn btn-sm btn-default',
                            'appendOptions'=>[
                                'class'=>function($model) { return !$model->products ? 'disabled' : ''; }
                            ]
                        ]
                    ]
                ],
            ],
        ]); ?>
    </div>
</div>
