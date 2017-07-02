<?php


return [
    'adminMenu'=>[
        ['label' => 'Каталог', 'icon' => 'list-alt', 'url' => '#', 'items'=>[
            ['label' => 'Категории', 'icon' => 'folder-o', 'url' => ['/catalog/admin/category']],
            ['label' => 'Товары', 'icon' => 'shopping-cart', 'url' => ['/catalog/admin/product']],
        ]],
    ]
];