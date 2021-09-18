<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use common\models\PartnerPayout;

/* @var $this yii\web\View */
/* @var $model common\models\Partner */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Partners', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);



?>
<div class="partner-view">

  <div>
    <div style="float:left">

      <h1>
        <?= Html::encode($this->title) ?>
      </h1>

      <p>
          <?= Html::a('Update', ['update', 'partner_uuid' => $model->partner_uuid, 'referral_code' => $model->referral_code], ['class' => 'btn btn-primary']) ?>



          <?= Html::a('Delete', ['delete', 'partner_uuid' => $model->partner_uuid, 'referral_code' => $model->referral_code], [
              'class' => 'btn btn-danger',
              'data' => [
                  'confirm' => 'Are you sure you want to delete this item?',
                  'method' => 'post',
              ],
          ]) ?>
      </p>
    </div>
    <div style="float:right; margin-bottom: 20px;">
        <?php
          if($model->totalEarnings > 0){ ?>

            <h1><?= 'Pending ' ?></h1>
            <h1>
              <?=  Yii::$app->formatter->asCurrency($model->totalEarnings ? $model->totalEarnings : 0, 'KWD',[ \NumberFormatter::MIN_FRACTION_DIGITS => 4, \NumberFormatter::MAX_FRACTION_DIGITS => 4 ]) ?>
            </h1>

            <?=  Html::a('Create Payout', ['create-payout', 'partner_uuid' => $model->partner_uuid, 'referral_code' => $model->referral_code], [
              'class' => 'btn btn-success',
              'data' => [
                'confirm' => 'Are you sure you want to create a payout?',
                  'method' => 'post',
              ],
          ]) ;
          }

        ?>
      </div>




    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'partner_uuid',
            'username',
            // 'partner_auth_key',
            // 'partner_password_hash',
            // 'partner_password_reset_token',
            'partner_email:email',
            [
              'label' => 'Status',
              'value' => $model->status,
            ],
            'referral_code',
            // 'commission',
            // 'partner_created_at',
            // 'partner_updated_at',
        ],
    ]) ?>


    <h2>Payments</h2>

    <div class="card">

        <?php

        if($payments && $payments->totalCount) {
        echo GridView::widget([
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
              [
                  'label' => 'Customer Name',
                  'format' => 'raw',
                  'value' => function ($data) {
                      return $data->customer->customer_name ;
                  }
              ],
              'order_uuid',
              'payment_mode',
              'payment_current_status:ntext',
              [
                  'label' => 'Referral commission ',
                  'attribute' => 'partner_fee',
                  'format' => 'raw',
                  'value' => function ($data) {
                      return Yii::$app->formatter->asCurrency($data->partner_fee, $data->currency->code,[ \NumberFormatter::MIN_FRACTION_DIGITS => 3, \NumberFormatter::MAX_FRACTION_DIGITS => 3 ]);
                  }
              ],
              [
                  'label' => 'Status',
                  'attribute' => 'partner_payout_uuid',
                  'format' => 'raw',
                  'value' => function ($data) {
                    if($data->partner_payout_uuid)
                      return $data->partnerPayout->status;
                  }
              ],

          ]


        ]);
        }
        ?>

    </div>

</div>
