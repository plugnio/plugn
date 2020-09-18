<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\WebLink */
$this->params['restaurant_uuid'] = $restaurantUuid;

$this->title = 'Create Web Link';
$this->params['breadcrumbs'][] = ['label' => 'Web Links', 'url' => ['index', 'restaurantUuid' => $restaurantUuid]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="web-link-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
