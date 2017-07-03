<?php

/**
 *  @var \svsoft\yii\modules\main\components\BaseModule $parent
 *  @var string $id
 */

$moduleRoute = "/{$parent->id}/{$id}/";

return [
    'adminMenu'=>[
        ['label' => 'Каталог', 'icon' => 'list-alt', 'url' => '#', 'items'=>[
            ['label' => 'Категории', 'icon' => 'folder-o', 'url' => [$moduleRoute . 'category/index']],
            ['label' => 'Товары', 'icon' => 'shopping-cart', 'url' => [$moduleRoute . 'product/index']],
        ]],
    ]
];