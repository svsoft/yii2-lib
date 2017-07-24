<?php

/**
 *  @var \svsoft\yii\modules\main\components\BaseModule $parent
 *  @var string $id
 */

$moduleRoute = "/{$parent->id}/{$id}/";

return [
    'adminMenu'=>[
        ['label' => 'Контент', 'icon' => 'shopping-cart', 'url' => '#', 'items'=>[
            ['label' => 'Документы', 'icon' => 'folder-o', 'url' => [$moduleRoute . 'document/documents']],
            //['label' => 'Корзина', 'icon' => 'folder-o', 'url' => [$moduleRoute . 'cart/index']],
        ]],
    ]
];