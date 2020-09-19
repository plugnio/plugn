<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BankDiscount */
$this->params['restaurant_uuid'] = $model->restaurant_uuid;

$this->title = 'Create Bank Discount';
$this->params['breadcrumbs'][] = ['label' => 'Bank Discounts', 'url' => ['index', 'restaurantUuid' => $restaurantUuid]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bank-discount-create">

    <?= $this->render('_form', [
        'model' => $model,
        'restaurantUuid' => $restaurantUuid
    ]) ?>

</div>
