<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model svsoft\yii\modules\shop\models\Order */

$this->title = $model->order_id;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-view box box-primary">
    <div class="box-header">
        <?= Html::a('Update', ['update', 'id' => $model->order_id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->order_id], [
            'class' => 'btn btn-danger btn-flat',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </div>
    <div class="box-body table-responsive no-padding">

        <?php
        foreach($model->getPropertyAsList() as $name=>$value)
            $properties[] = ['attribute'=>'test', 'label'=>$name, 'value'=>$value, 'format'=>'raw'];
        ?>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => \yii\helpers\ArrayHelper::merge([
                    'order_id',
                    'user_id',
                    'external_id',
                    'created:datetime',
                    'updated:datetime',
                    'total_price:currency',
                    'status_id',
                ], $properties) ,
        ]) ?>
    </div>
</div>
