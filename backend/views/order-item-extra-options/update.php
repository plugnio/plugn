<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\OrderItemExtraOption */

$this->title = 'Update Order Item Extra Option: ' . $model->order_item_extra_option_id;
$this->params['breadcrumbs'][] = ['label' => 'Order Item Extra Option', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->order_item_extra_option_id, 'url' => ['view', 'id' => $model->order_item_extra_option_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="order-item-extra-option-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
