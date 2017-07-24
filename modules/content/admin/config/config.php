<?php

/**
 *  @var \svsoft\yii\modules\main\components\BaseModule $parent
 *  @var string $id
 */

$moduleRoute = "/{$parent->id}/{$id}/";

return [
    'adminMenu'=>[
        ['label' => 'Контент', 'icon' => 'file-text-o', 'url' => '#', 'items'=>[
            ['label' => 'Документы', 'icon' => 'folder-o', 'url' => [$moduleRoute . 'document/documents']],
            ['label' => 'Блоки', 'icon' => 'folder-o', 'url' => [$moduleRoute . 'content-block/index']],
        ]],
    ]
];