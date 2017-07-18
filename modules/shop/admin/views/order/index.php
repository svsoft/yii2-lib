<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel svsoft\yii\modules\shop\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Create Order', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'order_id',
                //'user_id',
                //'external_id',
                'created:datetime',
                'updated:datetime',
                'total_price:currency',
                [
                    'attribute' => 'products',
                    'value' =>
                        function(\svsoft\yii\modules\shop\models\Order $model)
                        {
                            $products = $model->getCartItems()->with('product')->asArray()->select(['product_id'])->all();
                            $products = \yii\helpers\ArrayHelper::getColumn($products, 'product.name');
                            return  implode(', ', $products);
                        },
                ],

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>

        <? // Yii::$app->formatter->asCurrency()?>
    </div>
</div>
