<?php
/**
 * Created by PhpStorm.
 * User: viktor
 * Date: 12.06.2017
 * Time: 8:19
 *
 * @var \svsoft\yii\modules\properties\models\data\PropertyObject[] $objects;
 */
?>

<? foreach($objects as $object):?>
    <h3>
        Модель: <?=$object->text_id?>
    </h3>
    <? foreach($object->getProperties() as $property):?>
        <div class="row">
            <div class="col-lg-3">
                <?=$property->name?>
            </div>
            <div class="col-lg-3">
                <?if ($property->isEmpty()):?>
                    не заполнено
                <?else:?>
                    <?=implode(', ',$property->getValuesAsArray())?>
                <?endif;?>
            </div>
        </div>
    <? endforeach;?>
<? endforeach;?>

