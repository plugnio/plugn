<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\OrderItemExtraOptions */

$this->title = 'Update Order Item Extra Options: ' . $model->order_item_extra_options_id;
$this->params['breadcrumbs'][] = ['label' => 'Order Item Extra Options', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->order_item_extra_options_id, 'url' => ['view', 'id' => $model->order_item_extra_options_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="order-item-extra-options-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
