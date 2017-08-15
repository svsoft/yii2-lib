<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model svsoft\yii\modules\catalog\models\Category */

if ($model->parent_id)
{
    foreach($model->parent->getChain() as $parent)
    {
        $this->params['breadcrumbs'][] = ['label' => $parent->name, 'url' => ['category/index', 'parent_id'=>$parent->category_id]];
        $this->title .= $parent->name . '/';
    }
}

$this->title .= 'Добавление категории';
$this->params['breadcrumbs'][] = 'Добавление категории';
?>
<div class="catalog-category-create">

    <?= $this->render('_form', [
    'model' => $model,
    'categories' => $categories
    ]) ?>

</div>
