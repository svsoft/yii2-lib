<?php
use \yii\helpers\Html;
/**
 * @var array $file
 */
?>

<img src="<?=$file['src']?>"/>
<div class="checkbox checkbox-primary">
    <?= Html::activeHiddenInput($model, "names[{$file['id']}]") ?>

    <?= Html::activeCheckbox($model, "unlinks[{$file['id']}]", ['label'=>'Удалить', 'value'=>$file['name']]) ?>
</div>
