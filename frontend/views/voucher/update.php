<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Voucher */

$this->params['restaurant_uuid'] = $model->restaurant_uuid;

$this->title = 'Update Voucher: ' . $model->code;
$this->params['breadcrumbs'][] = ['label' => 'Vouchers', 'url' => ['index', 'restaurantUuid' =>  $model->restaurant_uuid]];
$this->params['breadcrumbs'][] = ['label' => $model->code, 'url' => ['view', 'id' => $model->voucher_id, 'restaurantUuid' =>  $model->restaurant_uuid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="voucher-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
