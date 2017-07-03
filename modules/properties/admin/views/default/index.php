<?php

use yii\helpers\Url;

?>

<div class="admin2-default-index">
    <h1>Модуль дополнительных свойств</h1>

    <ul>
        <li>
            <a href="<?=Url::to(['object-type/index'])?>">Типы объектов</a>
        </li>
        <li>
            <a href="<?=Url::to(['group/index'])?>">Группы свойств</a>
        </li>
        <li>
            <a href="<?=Url::to(['property/index'])?>">Свойства</a>
        </li>
        <li>
            <a href="<?=Url::to(['value/index'])?>">Значения свойств</a>
        </li>
    </ul>

</div>
