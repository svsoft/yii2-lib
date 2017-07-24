<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model svsoft\yii\modules\content\models\ContentBlock */

$this->title = 'Create Content Block';
$this->params['breadcrumbs'][] = ['label' => 'Content Blocks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-block-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
