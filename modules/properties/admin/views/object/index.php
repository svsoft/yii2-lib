<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel svsoft\yii\modules\properties\models\PropertyObjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Property Objects';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="property-object-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Create Property Object', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'object_id',
                'model_id',
                'model_type_id',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>
