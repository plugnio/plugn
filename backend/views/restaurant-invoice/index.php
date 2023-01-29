<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\RestaurantInvoice;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\RestaurantInvoice */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Restaurant Invoices');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="restaurant-invoice-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Restaurant Invoice'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           // 'invoice_uuid',
            'invoice_number',
            'restaurantName',
          //  'payment_uuid',
            [
                'attribute' => 'amount',
                'value' => function($model) {
                    return $model->amount . ' ' . $model->currency_code;
                },
            ],

            //'currency_code',
            [

                'attribute' => 'invoice_status',
                'filter' => [
                    RestaurantInvoice::STATUS_LOCKED => 'Pending',
                    RestaurantInvoice::STATUS_UNPAID => 'Draft',
                    RestaurantInvoice::STATUS_PAID => 'Paid',
                ],
                'value' => function($model) {
                    if($model->invoice_status == RestaurantInvoice::STATUS_LOCKED)
                        return 'Pending';
                    else if($model->invoice_status == RestaurantInvoice::STATUS_UNPAID)
                        return 'Draft';
                    else if($model->invoice_status == RestaurantInvoice::STATUS_PAID)
                        return 'Paid';
                    return $model->amount . ' ' . $model->currency_code;
                },
            ],
            'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn', 'template' => "{view}" ],
        ],
    ]); ?>


</div>
