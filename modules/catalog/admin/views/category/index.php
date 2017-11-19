<?php

use yii\helpers\Html;
use yii\grid\GridView;
use svsoft\yii\modules\catalog\models\Category;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $parent Category */
/* @var $parent_id int */
/* @var $extendedMode int */
/* @var $searchModel \svsoft\yii\modules\catalog\models\CategorySearch */
?>
<div class="catalog-category-index box box-primary">
    <div class="box-header with-border">
        <? if ($parent_id):?>
            <?= Html::a('Редактировать', ['category/update', 'id'=>$parent_id], ['class' => 'btn btn-info btn-flat']) ?>
        <? endif;?>

        <?= Html::a('Добавить подкатегорию', ['create', 'parent_id'=>$parent_id], ['class' => 'btn btn-success btn-flat']) ?>
        <?if ($parent && $parent->products):?>
            <?= Html::a('Список товаров', ['product/index', 'category_id'=>$parent_id], ['class' => 'btn btn-info btn-flat']) ?>
        <?endif;?>

        <?= Html::a('Расширенный режим', ['category/index', 'parent_id'=>$parent_id, 'extendedMode'=>!$extendedMode], ['class' => 'btn btn-primary btn-flat pull-right' . ($extendedMode ? ' active': '')]) ?>

    </div>
    <div class="box-body table-responsive no-padding">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'layout' => "{items}\n{summary}\n{pager}",
            'filterModel' => $searchModel,
            'columns' => [
                [
                    'class' => 'yii\grid\SerialColumn',
                    'contentOptions' => ['class'=>'format-number'],
                    'headerOptions' => ['class'=>'format-number'],
                ],
                [
                    'class'          => \svsoft\yii\modules\admin\components\ActionColumn::className(),
                    'template'       => '<div class="btn-group btn-sm-group-3">{children}{products}{add-product}</div>',
                    'buttonOptions' => ['class'=>'btn btn-info btn-sm'],
                    'contentOptions' => ['class'=>'format-action'],
                    'headerOptions' => ['class'=>'format-action'],
                    'buttons'        => [
                        'add-product' => [

                            'url'=> function(Category $model) { return \yii\helpers\Url::to(['product/create','category_id'=>$model->category_id], ['class' => 'btn btn-success btn-flat']); },
                            'content'=>'<i class="fa fa-plus"></i>',
                            'class'=>'btn btn-sm btn-default',
                            'options'=>['title'=>'Добавить товар'],
                            'appendOptions'=>[
                                'class'=>function($model) { return $model->categories ? 'disabled' : ''; }
                            ]
                        ],
                        'children' => [

                            'url'=> function(Category $model) { return \yii\helpers\Url::to(['category/index', 'parent_id'=>$model->category_id]); },
                            'content'=>'<i class="fa fa-bars"></i>',
                            'class'=>'btn btn-sm btn-default',
                            'options'=>['title'=>'Список категорий товаров'],
                            'appendOptions'=>[
                                'class'=>function($model) { return $model->products ? 'disabled' : ''; }
                            ]
                        ],
                        'products' => [
                            'url'=> function(Category $model) { return \yii\helpers\Url::to(['product/index?category_id='.$model->category_id]); },
                            'content'=>'<i class="fa fa-shopping-cart"></i>',
                            'class'=>'btn btn-sm btn-default',
                            'options'=>['title'=>'Список товаров'],
                            'appendOptions'=>[
                                'class'=>function($model) { return $model->categories ? 'disabled' : ''; }
                            ]
                        ]
                    ]
                ],
                [
                    'attribute' => 'category_id',
                    'contentOptions' => ['class'=>'format-number'],
                    'headerOptions' => ['class'=>'format-number'],
                    'visible' => $extendedMode,
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
                    'visible' => $extendedMode,
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
                    'attribute' => 'created',
                    'contentOptions' => ['class'=>'format-date'],
                    'headerOptions' => ['class'=>'format-date'],
                    'format'=>'datetime',
                    'visible' => $extendedMode,
                ],
                [
                    'attribute' => 'updated',
                    'contentOptions' => ['class'=>'format-date'],
                    'headerOptions' => ['class'=>'format-date'],
                    'format'=>'datetime',
                    'visible' => $extendedMode,
                ],
                [
                    'class'=>\svsoft\yii\modules\admin\components\StatusColumn::className(),
                    'attribute' => 'active',
                ],
                [
                    'class'          => \svsoft\yii\modules\admin\components\ActionColumn::className(),
                    'template'       => '<div class="btn-group btn-sm-group-2">{update}{delete}</div>',
                    'buttonOptions' => ['class'=>'btn btn-info btn-sm'],
                    'contentOptions' => ['class'=>'format-action'],
                    'headerOptions' => ['class'=>'format-action'],
                    'buttons'        => [
                        'add-product' => [

                            'url'=> function(Category $model) { return \yii\helpers\Url::to(['product/create','category_id'=>$model->category_id], ['class' => 'btn btn-success btn-flat']); },
                            'content'=>'<i class="fa fa-plus"></i>',
                            'class'=>'btn btn-sm btn-default',
                            'appendOptions'=>[
                                'class'=>function($model) { return $model->categories ? 'disabled' : ''; }
                            ]
                        ],
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
