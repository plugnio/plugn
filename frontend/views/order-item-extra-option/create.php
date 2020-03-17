<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\OrderItemExtraOptions */

$this->title = 'Create Order Item Extra Options';
$this->params['breadcrumbs'][] = ['label' => $model->orderItem->item_name , 'url' => ['order-item/view' , 'id' => $model->order_item_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-item-extra-options-create">


    <?= $this->render('_form', [
        'model' => $model,
        'extraOptionsQuery' => $extraOptionsQuery
    ]) ?>

</div>