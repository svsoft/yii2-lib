<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model svsoft\yii\modules\catalog\models\Product */

$this->title = 'Create Product';
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-create">

    <?= $this->render('_form', [
    'model' => $model,
    'categories' => $categories
    ]) ?>

</div>
