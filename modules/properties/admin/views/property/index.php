<?php

use yii\helpers\Html;
use yii\grid\GridView;
use svsoft\yii\modules\properties\components\PropertyHelper;

/* @var $this yii\web\View */
/* @var $searchModel svsoft\yii\modules\properties\models\PropertySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Properties';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="property-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Create Property', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'property_id',
                'name',
                'slug',
                [
                    'attribute' => 'model_type_id',
                    'value' => 'modelType.name',
                    'filter' => PropertyHelper::getModelTypeList()
                ],
                [
                    'attribute' => 'group_id',
                    'value' => 'group.name',
                    'filter' => PropertyHelper::getGroupList($searchModel->model_type_id),
                ],
                 'type',
                 'multiple',
                 'active',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>
