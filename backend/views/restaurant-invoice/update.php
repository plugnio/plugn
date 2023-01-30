<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RestaurantInvoice */

$this->title = Yii::t('app', 'Update Restaurant Invoice: {name}', [
    'name' => $model->invoice_uuid,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Restaurant Invoices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->invoice_uuid, 'url' => ['view', 'id' => $model->invoice_uuid]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="restaurant-invoice-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
