<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel svsoft\yii\modules\shop\models\CartItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cart Items';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cart-item-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Create Cart Item', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'item_id',
                'product_id',
                'order_id',
                'user_id',
                'session_id',
                // 'price',
                // 'count',
                // 'created',
                // 'updated',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>
