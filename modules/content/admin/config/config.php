<?php

/**
 *  @var \svsoft\yii\modules\main\components\BaseModule $parent
 *  @var string $id
 */

return [
    'adminMenu'=>[
        ['label' => 'Контент', 'icon' => 'file-text-o', 'url' => '#', 'items'=>[
            ['label' => 'Документы', 'icon' => 'folder-o', 'url' => ['/admin/content/document/documents']],
            ['label' => 'Блоки', 'icon' => 'folder-o', 'url' => ['/admin/content/content-block/index']],
        ]],
    ]
];