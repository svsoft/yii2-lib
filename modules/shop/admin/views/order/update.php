<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model svsoft\yii\modules\shop\models\Order */

$this->title = 'Update Order: ' . $model->order_id;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->order_id, 'url' => ['view', 'id' => $model->order_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="order-update">

    <?=$this->render('_update_menu', ['model' => $model])?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
