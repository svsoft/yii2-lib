<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model svsoft\yii\modules\shop\models\CartItem */

$this->title = 'Update Cart Item: ' . $model->item_id;
$this->params['breadcrumbs'][] = ['label' => 'Cart Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->item_id, 'url' => ['view', 'id' => $model->item_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cart-item-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
