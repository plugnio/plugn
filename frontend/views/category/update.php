<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Category */

$this->params['restaurant_uuid'] = $restaurantUuid;

$this->title = 'Update Category: ' . $model->category_name;
$this->params['breadcrumbs'][] = ['label' => 'Categories', 'url' => ['index', 'restaurantUuid' => $restaurantUuid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="category-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
