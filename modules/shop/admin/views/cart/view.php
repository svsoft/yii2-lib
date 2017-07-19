<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model svsoft\yii\modules\shop\models\CartItem */

$this->title = $model->item_id;
$this->params['breadcrumbs'][] = ['label' => 'Cart Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cart-item-view box box-primary">
    <div class="box-header">
        <?= Html::a('Update', ['update', 'id' => $model->item_id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->item_id], [
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
                'item_id',
                'product_id',
                'order_id',
                'user_id',
                'session_id',
                'price',
                'count',
                'created',
                'updated',
            ],
        ]) ?>
    </div>
</div>
