<?php

/* @var $this yii\web\View */
/* @var $model svsoft\yii\modules\catalog\models\Category */
/* @var $categories svsoft\yii\modules\catalog\models\Category[] */

?>
<div class="catalog-category-update">

    <?=$this->render('_update_menu', ['model' => $model])?>

    <?= $this->render('_form', [
        'model' => $model,
        'categories' => $categories,
    ]) ?>

</div>
