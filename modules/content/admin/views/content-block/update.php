<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model svsoft\yii\modules\content\models\ContentBlock */

$this->title = 'Update Content Block: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Content Blocks', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->block_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="content-block-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
