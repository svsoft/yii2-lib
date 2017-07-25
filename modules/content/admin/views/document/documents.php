<?php

use yii\helpers\Html;
use yii\grid\GridView;
use svsoft\yii\modules\content\models\Document;

/* @var $this yii\web\View */
/* @var $searchModel svsoft\yii\modules\content\models\DocumentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $parent Document */



foreach($parentChain as $document)
{
    $this->params['breadcrumbs'][] = ['label' => $document->name, 'url' => ['document/documents', 'parent_id'=>$document->document_id]];
    $this->title .= $document->name . '/';
}

if ($parent->document_id)
    $this->title .= $parent->name;
else
    $this->title .= 'Документы';



$this->params['breadcrumbs'][] = $this->title;

?>
<div class="document-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Добавить документ', ['create', 'parent_id'=>$parent->document_id], ['class' => 'btn btn-success btn-flat']) ?>

        <? if ($parent->document_id):?>
        <?= Html::a('Вернуться в раздел', ['documents', 'parent_id'=>$parent->parent_id], ['class' => 'btn btn-info btn-flat']) ?>
        <? endif;?>
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
                    'class'          => 'yii\grid\ActionColumn',
                    'template'       => '{children}{view}<span class="button-separator"></span>{copy}{update}{delete}',
                    //'template'       => '<div>{copy}</div><div>{view}</div><div>{update}</div><div>{delete}</div>',
                    'visibleButtons' => [
                        'children' => function(Document $model){ return $model->children;},
                    ],
                    'buttonOptions' => ['class'=>'btn btn-info btn-xs button'],
                    'buttons'        => [
                        'children' => function ($url, $model, $key)
                        {
                            return Html::a(Html::tag('i','',['class'=>'fa fa-bars']), ['document/documents', 'parent_id'=>$model->document_id], ['class' => 'btn btn-info btn-xs button']);
                        },
                        'copy' => function ($url, $model, $key)
                        {
                            return Html::a(Html::tag('i','',['class'=>'fa fa-copy']), ['document/create?copy='.$model->document_id], ['class' => 'btn btn-info btn-xs button']);
                        },
                    ]
                ],
            ],
        ]);?>
    </div>
</div>
