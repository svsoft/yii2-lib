<?php
?>

<?=\yii\widgets\Menu::widget([
    'options' => [
        'class'=>'nav nav-tabs'],
    'items'=>[
        [ 'label'=>'Товар', 'url'=>['product/update','id'=>$model->product_id]],
        [ 'label'=>'Свойства', 'url'=>['product/properties', 'id'=>$model->product_id]]
    ]]);
?>
