<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use svsoft\yii\modules\properties\models\data\Property;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $propertyForms \svsoft\yii\modules\properties\models\forms\PropertyForm[] */
/* @var $groups \svsoft\yii\modules\properties\models\data\PropertyGroup[] */
/* @var $allGroups \svsoft\yii\modules\properties\models\data\PropertyGroup[] */
/* @var $object \svsoft\yii\modules\properties\models\data\PropertyObject */

$allGroups = $object->modelType->getPropertyGroups()->indexBy('group_id')->all();
$groups = $object->getGroupsWithProperties();

?>

<div class="properties-form box box-primary">

    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <?$first=true;?>
                <?foreach($groups as $groupId=>$group):?>
                    <li class="<?=$first ? 'active' : ''?>"><a href="#tab_<?=$groupId?>" data-toggle="tab"><?=$group->name?></a></li>
                    <?$first=false;?>
                <?endforeach;?>

                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        Настройки <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">

                        <?foreach($allGroups as $groupId=>$group):?>
                            <li role="presentation">
                                <? if (empty($groups[$groupId])):?>
                                    <a role="menuitem" tabindex="-1" href="<?=Url::to(['/properties/admin/properties/link-group', 'object_id'=>$object->object_id, 'group_id'=>$groupId])?>" data-confirm="Не сохранненные данные будут потеряны, продолжить?">
                                        <i class="fa fa-plus color-add"></i><?=$group->name?>
                                    </a>
                                <? else:?>
                                    <a role="menuitem" tabindex="-1" href="<?=Url::to(['/properties/admin/properties/unlink-group', 'object_id'=>$object->object_id, 'group_id'=>$groupId])?>" data-confirm="Не сохранненные данные будут потеряны, продолжить?">
                                        <?if (!$group->require):?><i class="fa fa-close color-delete"></i><?endif;?><?=$group->name?>
                                    </a>
                                <? endif;?>
                            </li>
                        <?endforeach;?>

                    </ul>
                </li>
                <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>
            </ul>
            <div class="tab-content">
                <?$first=true;?>
                <?foreach($groups as $groupId=>$group):?>
                    <div class="tab-pane <?=$first ? 'active' : ''?>" id="tab_<?=$groupId?>">

                        <? foreach($group->activeProperties as $property):?>
                            <?$propertyForm = $propertyForms[$property->property_id]?>
                            <?=$this->render('_field', ['form' => $form, 'propertyForm' => $propertyForm])?>
                        <?endforeach;?>
                        <?$first=false;?>
                    </div>
                <?endforeach;?>


                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_2">
                </div>
                <!-- /.tab-pane -->

            </div>
            <!-- /.tab-content -->
        </div>
    </div>
<?/*
    <div class="box-body table-responsive row ">
        <div class="">

            <? foreach($groups as $group):?>
                <div class="col-md-6">
                    <div class="box box-solid box-primary">

                        <div class="box-header">
                            <h3 class="box-title"><?=Html::encode($group->name)?></h3>
                        </div>

                        <div class="box-body">
                            <? foreach($group->properties as $property):?>
                                <?$propertyForm = $propertyForms[$property->property_id]?>
                                <?=$this->render('_field', ['form' => $form, 'propertyForm' => $propertyForm])?>
                            <?endforeach;?>
                        </div>
                    </div>
                </div>
            <?endforeach;?>
        </div>
    </div>

*/?>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<div class="">

</div>
