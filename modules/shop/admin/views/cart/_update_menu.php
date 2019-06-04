<?php
/**
 * @var $model \svsoft\yii\modules\shop\models\CartItem
 */
?>

<?=\yii\widgets\Menu::widget([
    'options' => [
        'class'=>'nav nav-tabs'],
    'items'=>[
        [ 'label'=>'Заказ', 'url'=>['cart/update','id'=>$model->item_id]],
        [ 'label'=>'Свойства', 'url'=>['cart/properties', 'id'=>$model->item_id]]
    ]]);
?>
