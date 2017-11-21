<?php

/**
 *  @var \svsoft\yii\modules\main\components\BaseModule $parent
 *  @var string $id
 */

return [
    'adminMenu'=>[
        ['label' => 'Каталог товаров', 'icon' => 'list-alt', 'url' => '#', 'items'=>[
            ['label' => 'Каталог', 'icon' => 'folder-o', 'url' => ['/admin/catalog/category/index']],
            ['label' => 'Все товары', 'icon' => 'shopping-cart', 'url' => ['/admin/catalog/product/index']],
        ]],
    ]
];