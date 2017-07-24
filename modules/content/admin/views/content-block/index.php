<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel svsoft\yii\modules\content\models\ContentBlockSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Content Blocks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-block-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Create Content Block', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'block_id',
                'name',
                'slug',
                'content:ntext',
                'created',
                // 'updated',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>
