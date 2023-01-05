<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\PaymentMethod */

$this->title = $model->payment_method_name;
$this->params['breadcrumbs'][] = ['label' => 'Payment Methods', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="payment-method-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <!--
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->payment_method_id], ['class' => 'btn btn-primary btn-update']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->payment_method_id], [
            'class' => 'btn btn-danger btn-delete',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>-->

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'payment_method_id',
            'payment_method_name',
            'payment_method_name_ar',
            'payment_method_code',
            'vat',
            'source_id',
            [
                'label' => 'Supported Currencies',
                'value' => function($model) {
                    $cs = \yii\helpers\ArrayHelper::getColumn($model->paymentMethodCurrencies, 'currency');

                    return implode(', ', $cs);
                }
            ],
        ],
    ]) ?>

</div>
