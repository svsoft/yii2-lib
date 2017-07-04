<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel svsoft\yii\modules\properties\models\PropertyGroupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Property Groups';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="property-group-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Create Property Group', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'group_id',
                'name',
                'slug',
                'model_type_id',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>
