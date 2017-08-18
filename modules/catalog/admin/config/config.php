<?php

/**
 *  @var \svsoft\yii\modules\main\components\BaseModule $parent
 *  @var string $id
 */

return [
    'adminMenu'=>[
        ['label' => 'Каталог', 'icon' => 'list-alt', 'url' => '#', 'items'=>[
            ['label' => 'Категории', 'icon' => 'folder-o', 'url' => ['/admin/catalog/category/index']],
            ['label' => 'Товары', 'icon' => 'shopping-cart', 'url' => ['/admin/catalog/product/index']],
        ]],
    ]
];