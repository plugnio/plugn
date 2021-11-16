<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\PartnerPayout */

$this->title = $model->partner_payout_uuid;
$this->params['breadcrumbs'][] = ['label' => 'Partner Payouts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="partner-payout-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->partner_payout_uuid], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->partner_payout_uuid], [
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
            'partner_payout_uuid',
            'partner_uuid',
            'amount',
            'created_at',
            'updated_at',
            'status',
            'transferFile',
        ],
    ]) ?>



        <h2>Payments</h2>

        <div class="card">


              <?=  GridView::widget([
                          'dataProvider' => $payments,
                          'columns' => [
                          'payment_uuid',
                          [
                              'label' => 'Store Name',
                              'format' => 'raw',
                              'value' => function ($data) {
                                  return $data->restaurant->name ;
                              }
                          ],

                          'order_uuid',
                          'payment_mode',
                          'payment_current_status:ntext',
                          [
                              'attribute' => 'payment_amount_charged',
                              'format' => 'raw',
                              'value' => function ($data) {
                                  return Yii::$app->formatter->asCurrency($data->payment_amount_charged, $data->currency->code,[
                                          \NumberFormatter::MIN_FRACTION_DIGITS => $data->currency->decimal_place,
                                          \NumberFormatter::MAX_FRACTION_DIGITS => $data->currency->decimal_place
                                  ]);
                              }
                          ],
                          [
                              'attribute' => 'payment_gateway_fee',
                              'format' => 'raw',
                              'value' => function ($data) {
                                  return Yii::$app->formatter->asCurrency($data->payment_gateway_fee, $data->currency->code,[
                                      \NumberFormatter::MIN_FRACTION_DIGITS => $data->currency->decimal_place,
                                      \NumberFormatter::MAX_FRACTION_DIGITS => $data->currency->decimal_place
                                  ]);
                              }
                          ],
                          [
                              'attribute' => 'plugn_fee',
                              'format' => 'raw',
                              'value' => function ($data) {
                                  return Yii::$app->formatter->asCurrency($data->plugn_fee, $data->currency->code,[
                                      \NumberFormatter::MIN_FRACTION_DIGITS => $data->currency->decimal_place,
                                      \NumberFormatter::MAX_FRACTION_DIGITS => $data->currency->decimal_place
                                  ]);
                              }
                          ],
                          [
                              'attribute' => 'partner_fee',
                              'format' => 'raw',
                              'value' => function ($data) {
                                  return Yii::$app->formatter->asCurrency($data->partner_fee, $data->currency->code,[
                                      \NumberFormatter::MIN_FRACTION_DIGITS => $data->currency->decimal_place,
                                      \NumberFormatter::MAX_FRACTION_DIGITS => $data->currency->decimal_place
                                  ]);
                              }
                          ],
                          [
                              'attribute' => 'payment_net_amount',
                              'format' => 'raw',
                              'value' => function ($data) {
                                  return Yii::$app->formatter->asCurrency($data->payment_net_amount, $data->currency->code,[
                                      \NumberFormatter::MIN_FRACTION_DIGITS => $data->currency->decimal_place,
                                      \NumberFormatter::MAX_FRACTION_DIGITS => $data->currency->decimal_place
                                  ]);
                              }
                          ],
                          'payment_created_at',
                          [
                              'label' => 'Status',
                              'attribute' => 'partner_payout_uuid',
                              'format' => 'raw',
                              'value' => function ($data) {
                                if($data->partner_payout_uuid)
                                  return $data->partnerPayout->status;
                              }
                          ]
                      ],
                  ]) ?>
        </div>

        <h2>Subscription Payment</h2>

        <div class="card">


              <?=  GridView::widget([
                          'dataProvider' => $subscriptionPayments,
                          'columns' => [
                          'payment_uuid',
                          [
                              'label' => 'Store Name',
                              'format' => 'raw',
                              'value' => function ($data) {
                                  return $data->restaurant->name ;
                              }
                          ],
                          'payment_mode',
                          'payment_current_status:ntext',
                          [
                              'attribute' => 'payment_amount_charged',
                              'format' => 'raw',
                              'value' => function ($data) {
                                  return Yii::$app->formatter->asCurrency($data->payment_amount_charged, $data->currency->code,[
                                      \NumberFormatter::MIN_FRACTION_DIGITS => $data->currency->decimal_place,
                                      \NumberFormatter::MAX_FRACTION_DIGITS => $data->currency->decimal_place
                                  ]);
                              }
                          ],
                          [
                              'attribute' => 'payment_gateway_fee',
                              'format' => 'raw',
                              'value' => function ($data) {
                                  return Yii::$app->formatter->asCurrency($data->payment_gateway_fee, $data->currency->code,[
                                      \NumberFormatter::MIN_FRACTION_DIGITS => $data->currency->decimal_place,
                                      \NumberFormatter::MAX_FRACTION_DIGITS => $data->currency->decimal_place
                                  ]);
                              }
                          ],
                          [
                              'attribute' => 'partner_fee',
                              'format' => 'raw',
                              'value' => function ($data) {
                                  return Yii::$app->formatter->asCurrency($data->partner_fee, $data->currency->code,[
                                      \NumberFormatter::MIN_FRACTION_DIGITS => $data->currency->decimal_place,
                                      \NumberFormatter::MAX_FRACTION_DIGITS => $data->currency->decimal_place
                                  ]);
                              }
                          ],
                          [
                              'attribute' => 'payment_net_amount',
                              'format' => 'raw',
                              'value' => function ($data) {
                                  return Yii::$app->formatter->asCurrency($data->payment_net_amount, $data->currency->code,[
                                      \NumberFormatter::MIN_FRACTION_DIGITS => $data->currency->decimal_place,
                                      \NumberFormatter::MAX_FRACTION_DIGITS => $data->currency->decimal_place
                                  ]);
                              }
                          ],
                          'payment_created_at',
                          [
                              'label' => 'Status',
                              'attribute' => 'partner_payout_uuid',
                              'format' => 'raw',
                              'value' => function ($data) {
                                if($data->partner_payout_uuid)
                                  return $data->partnerPayout->status;
                              }
                          ]
                      ],
                  ]) ?>
        </div>



</div>
