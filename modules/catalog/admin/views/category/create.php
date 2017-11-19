<?php

/* @var $this yii\web\View */
/* @var $model svsoft\yii\modules\catalog\models\Category */
/* @var $categories svsoft\yii\modules\catalog\models\Category[] */

?>
<div class="catalog-category-create">

    <?= $this->render('_form', [
    'model' => $model,
    'categories' => $categories
    ]) ?>

</div>
