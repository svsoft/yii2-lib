<?php

/**
 *  @var \svsoft\yii\modules\main\components\BaseModule $parent
 *  @var string $id
 */

$moduleRoute = "/{$parent->id}/{$id}/";

return [
    'adminMenu'=>[
        ['label' => 'Интернет магазин', 'icon' => 'shopping-cart', 'url' => '#', 'items'=>[
            ['label' => 'Заказы', 'icon' => 'folder-o', 'url' => [$moduleRoute . 'order/index']],
            ['label' => 'Корзина', 'icon' => 'folder-o', 'url' => [$moduleRoute . 'cart/index']],
        ]],
    ]
];