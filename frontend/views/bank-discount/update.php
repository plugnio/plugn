<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BankDiscount */
$this->params['restaurant_uuid'] = $model->restaurant_uuid;

$this->title = 'Update Bank Discount: ' . $model->bank->bank_name;
$this->params['breadcrumbs'][] = ['label' => 'Bank Discounts', 'url' => ['index', 'storeUuid' =>  $model->restaurant_uuid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="bank-discount-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
