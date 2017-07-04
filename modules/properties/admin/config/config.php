<?php

/**
 *  @var \svsoft\yii\modules\main\components\BaseModule $parent
 *  @var string $id
 */

$moduleRoute = "/{$parent->id}/{$id}/";

return [
    'adminMenu'=>[
        ['label' => 'Свойства', 'icon' => 'check', 'url' => '#', 'items'=>[
            ['label' => 'Типы объектов', 'icon' => 'circle-o', 'url' => [$moduleRoute . 'model-type/index']],
            ['label' => 'Привязка к объекта', 'icon' => 'circle-o', 'url' => [$moduleRoute . 'object/index']],
            ['label' => 'Группы свойств', 'icon' => 'circle-o', 'url' => [$moduleRoute . 'group/index']],
            ['label' => 'Свойства', 'icon' => 'circle-o', 'url' => [$moduleRoute . 'property/index']],
            ['label' => 'Значения свойств', 'icon' => 'circle-o', 'url' => [$moduleRoute . 'value/index']],
        ]],
    ]
];

