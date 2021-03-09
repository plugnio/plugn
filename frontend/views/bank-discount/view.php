<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\BankDiscount */

$this->title = $model->bank_discount_id;
$this->params['breadcrumbs'][] = ['label' => 'Bank Discounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="bank-discount-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->bank_discount_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->bank_discount_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'bank_discount_id',
            'bank_id',
            'restaurant_uuid',
            'discount_type',
            'discount_amount',
            'bank_discount_status',
            'valid_from',
            'valid_until',
            'max_redemption',
            'limit_per_customer',
            'minimum_order_amount',
            'bank_discount_created_at',
            'bank_discount_updated_at',
        ],
    ]) ?>

</div>
