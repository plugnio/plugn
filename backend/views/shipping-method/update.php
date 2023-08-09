<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ShippingMethod */

$this->title = Yii::t('app', 'Update Shipping Method: {name}', [
    'name' => $model->shipping_method_id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Shipping Methods'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->shipping_method_id, 'url' => ['view', 'id' => $model->shipping_method_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="shipping-method-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
