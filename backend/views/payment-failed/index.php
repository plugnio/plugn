<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PaymentFailedSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Payment Faileds');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-failed-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Payment Failed'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'payment_failed_uuid',

            [
                'attribute' => 'payment_uuid',
                "format" => "raw",
                'value' => function ($model) {

                        return Html::a($model->payment_uuid, \yii\helpers\Url::to(['payment/view', 'id' => $model->payment_uuid]), [
                            "target" => "_blank"
                        ]);
                }
            ],

            [
                'attribute' => 'order_uuid',
                "format" => "raw",
                'value' => function ($model) {
                   return Html::a($model->order_uuid, \yii\helpers\Url::to(['order/view', 'id' => $model->order_uuid]), [
                           "target" => "_blank"
                   ]);
                }
            ],
            //'customer_id',
            [
                'attribute' => 'restaurantName',
                "format" => "raw",
                'value' => function ($model) {
                    if($model->restaurant) {
                        return Html::a($model->restaurant->name, \yii\helpers\Url::to(['restaurant/view', 'id' => $model->order->restaurant_uuid]), [
                            "target" => "_blank"
                        ]);
                    }
                }
            ],
            //'response:ntext',
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
