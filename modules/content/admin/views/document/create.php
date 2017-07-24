<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model svsoft\yii\modules\content\models\Document */
/* @var $parentChain svsoft\yii\modules\content\models\Document[] */

$this->title = 'Добавить документ';

foreach($parentChain as $document)
    $this->params['breadcrumbs'][] = ['label' => $document->name, 'url' => ['document/documents', 'parent_id'=>$document->document_id]];


$this->params['breadcrumbs'][] = $this->title;

?>
<div class="document-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
