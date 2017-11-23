<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \svsoft\yii\modules\catalog\models\Product;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel svsoft\yii\modules\catalog\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $category \svsoft\yii\modules\catalog\models\Category */
/* @var $extendedMode boolean */
/* @var $category_id int */

?>
<div class="product-index box box-primary">
    <div class="box-header with-border">
        <?if($category):?>
            <?= Html::a('Редактировать', ['category/update', 'id'=>$category_id], ['class' => 'btn btn-info btn-flat']) ?>
            <?= Html::a('Добавить товар', ['create','category_id'=>$category->category_id], ['class' => 'btn btn-success btn-flat']) ?>
        <?endif;?>
        <?= Html::a('Расширенный режим', Url::current(['extendedMode'=>!$extendedMode]) , ['class' => 'btn btn-primary btn-flat pull-right' . ($extendedMode ? ' active': '')]) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?php  // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                [
                    'class' => 'yii\grid\SerialColumn',
                    'contentOptions' => ['class'=>'format-number'],
                    'headerOptions' => ['class'=>'format-number'],
                ],

                [
                    'attribute' => 'product_id',
                    'contentOptions' => ['class'=>'format-number'],
                    'headerOptions' => ['class'=>'format-number'],
                    'visible' => $extendedMode,
                ],
                [
                    'attribute' => 'images',
                    'format'=>'html',
                    'contentOptions' => ['class'=>'format-images'],
                    'headerOptions' => ['class'=>'format-images'],
                    'value'=>
                        function(Product $model) {
                            return Html::img(Yii::$app->image->crop( $model->getFirstFileSrc(),'admin-small'));
                        },
                    'filter' => false,
                ],
                [
                    'attribute' => 'name',
                    'format'=>'ntext',
                    'contentOptions' => ['class'=>'format-text'],
                    'headerOptions' => ['class'=>'format-text'],
                ],
                [
                    'attribute' => 'category_id',
                    'value' => 'category.name',
                    'contentOptions' => ['class'=>'format-text'],
                    'headerOptions' => ['class'=>'format-text'],
                    'visible' => !$category_id,
                    'filter' => \svsoft\yii\modules\catalog\components\CatalogHelper::getCategoryListWithStructure(null, false),
                ],
                [
                    'attribute' => 'slug',
                    'format'=>'ntext',
                    'contentOptions' => ['class'=>'format-text'],
                    'headerOptions' => ['class'=>'format-text'],
                    'visible' => $extendedMode,
                ],
                [
                    'attribute' => 'description',
                    'format'=>'ntext',
                    'contentOptions' => ['class'=>'format-text'],
                    'headerOptions' => ['class'=>'format-text'],
                    'visible' => $extendedMode,
                ],
                [
                    'attribute' => 'price',
                    'contentOptions' => ['class'=>'format-currency'],
                    'headerOptions' => ['class'=>'format-currency'],
                    'format' => 'currency',
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



                // 'count',
                // 'measure',
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
                    'class'          => \svsoft\yii\modules\admin\components\ActionColumn::className(),
                    'template'       => '<div class="btn-group btn-sm-group-2">{update}{delete}</div>',
                    'buttonOptions' => ['class'=>'btn btn-info btn-sm'],
                    'contentOptions' => ['class'=>'format-action'],
                    'headerOptions' => ['class'=>'format-action'],

                ],
            ],
        ]); ?>
    </div>
</div>
