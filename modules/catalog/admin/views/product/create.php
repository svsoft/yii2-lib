<?php


/* @var $this yii\web\View */
/* @var $model svsoft\yii\modules\catalog\models\Product */
/* @var $category \svsoft\yii\modules\catalog\models\Category */
/* @var $categories svsoft\yii\modules\catalog\models\Category[] */

?>
<div class="product-create">

    <?= $this->render('_form', [
    'model' => $model,
    'categories' => $categories
    ]) ?>

</div>
