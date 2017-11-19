<?php

/* @var $this yii\web\View */
/* @var $model svsoft\yii\modules\catalog\models\Product */
/* @var $categories svsoft\yii\modules\catalog\models\Category[] */
?>


<div class="product-update">

    <?=$this->render('_update_menu', ['model' => $model])?>

    <?= $this->render('_form', [
        'model' => $model,
        'categories' => $categories
    ]) ?>

</div>
