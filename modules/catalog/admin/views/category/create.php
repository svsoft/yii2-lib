<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model svsoft\yii\modules\catalog\models\Category */


$this->title = 'Create Catalog Category';
$this->params['breadcrumbs'][] = ['label' => 'Catalog Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="catalog-category-create">

    <?= $this->render('_form', [
    'model' => $model,
    'categories' => $categories
    ]) ?>

</div>
