<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model svsoft\yii\modules\shop\models\CartItem */

$this->title = 'Create Cart Item';
$this->params['breadcrumbs'][] = ['label' => 'Cart Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cart-item-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
