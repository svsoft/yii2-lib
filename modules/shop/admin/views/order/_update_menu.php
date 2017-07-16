<?php
?>

<?=\yii\widgets\Menu::widget([
    'options' => [
        'class'=>'nav nav-tabs'],
    'items'=>[
        [ 'label'=>'Заказ', 'url'=>['order/update','id'=>$model->order_id]],
        [ 'label'=>'Свойства', 'url'=>['order/properties', 'id'=>$model->order_id]]
    ]]);
?>
