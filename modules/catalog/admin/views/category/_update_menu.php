<?php
?>

<?=\yii\widgets\Menu::widget([
    'options' => [
        'class'=>'nav nav-tabs'],
    'items'=>[
        [ 'label'=>'Категория', 'url'=>['category/update','id'=>$model->category_id]],
        [ 'label'=>'Свойства', 'url'=>['category/properties', 'id'=>$model->category_id]]
    ]]);
?>
