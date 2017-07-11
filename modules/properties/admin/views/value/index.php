<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel svsoft\yii\modules\properties\models\PropertyValueSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Property Values';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="property-value-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Create Property Value', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'value_id',
                'property_id',
                'object_id',
                [
                    'attribute' => 'property',
                    'value' => 'property.name',
                ],
                [
                    'attribute' => 'propertyType',
                    'value' => 'property.typeName',
                ],
                [
                    'attribute' => 'modelType',
                    'value' => 'object.modelType.name',
                ],
                [
                    'attribute'=>'model',
                    'value' => function($model){
                        return $model->object->model->modelName;
                    },
                ],
                'value',
                //'string_value',
                //'text_value:ntext',
                // 'int_value',
                // 'float_value',
                // 'timestamp_value:datetime',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>
