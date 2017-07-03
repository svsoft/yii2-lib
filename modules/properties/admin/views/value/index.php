<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel svsoft\yii\modules\properties\PropertyValueSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Property Values';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="property-value-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Property Value', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'value_id',
            'property_id',
            'object_id',
            'object_type_id',
            'string_value',
            // 'text_value:ntext',
            // 'int_value',
            // 'float_value',
            // 'timestamp_value:datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
