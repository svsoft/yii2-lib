<?php

use yii\helpers\Html;
use yii\grid\GridView;
use svsoft\yii\modules\content\models\Document;

/* @var $this yii\web\View */
/* @var $searchModel svsoft\yii\modules\content\models\DocumentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $parent Document */

$this->title = 'Документы';

if ($parent->document_id)
    $this->title = $parent->name;

foreach($parentChain as $document)
    $this->params['breadcrumbs'][] = ['label' => $document->name, 'url' => ['document/documents', 'parent_id'=>$document->document_id]];

$this->params['breadcrumbs'][] = $this->title;

?>
<div class="document-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Добавить документ', ['create', 'parent_id'=>$parent->document_id], ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'attribute' =>'',
                    'value' =>  function(Document $model){ return Html::tag('i', '', ['class'=>$model->children ? 'fa fa-folder-o' : 'fa fa-file-text-o']); },
                    'format' => 'html',
                ],
                'document_id',

                'name',
                'slug',

                [
                    'attribute'=>'contents',
                    'value' => function(Document $model){ return Html::a('Документы', ['document/documents', 'parent_id'=>$model->document_id]); },
                    'format' => 'html',
                ],
                // 'content:ntext',
                // 'preview:ntext',
                // 'active',
                // 'created',
                // 'updated',
                // 'sort',
                // 'images:ntext',
                // 'title',
                // 'h1',
                // 'description:ntext',

                [
                    'class' => 'yii\grid\ActionColumn',
                    'buttons' => [
                        //'delete' => function($url, $model) { return Html::a('Удалить', $url);},
                    ]
                ],
            ],
        ]);?>
    </div>
</div>
