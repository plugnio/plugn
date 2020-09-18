<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Voucher */

$this->params['restaurant_uuid'] = $restaurantUuid;

$this->title = 'Create Voucher';
$this->params['breadcrumbs'][] = ['label' => 'Vouchers', 'url' => ['index', 'restaurantUuid' => $restaurantUuid]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="voucher-create">

    <?= $this->render('_form', [
        'model' => $model,
        'restaurantUuid' => $restaurantUuid
    ]) ?>

</div>
