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
          if($totalEarnings > 0){ ?>

            <h1><?= 'Pending ' ?></h1>
            <h1>
              <?=  Yii::$app->formatter->asCurrency($totalEarnings ? $totalEarnings : 0, 'KWD',[ \NumberFormatter::MIN_FRACTION_DIGITS => 4, \NumberFormatter::MAX_FRACTION_DIGITS => 4 ]) ?>
            </h1>

            <?=  Html::a('Create Payout', ['create-payout', 'partner_uuid' => $model->partner_uuid, 'referral_code' => $model->referral_code], [
              'class' => 'btn btn-success',
              'data' => [
                  'confirm' => 'Are you sure you want to mark as paid?',
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


    <h2>Payouts</h2>

    <div class="card">

        <?=
        GridView::widget([
            'dataProvider' => $payouts,
            'columns' => [
                'amount',
                'created_at:datetime',

                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => ' {view} ',
                    'buttons' => [
                      'view' => function ($url, $model) {
                          if($model->payout_status == PartnerPayout::PAYOUT_STATUS_PENDING){
                            return Html::a(
                                        'Mark as Paid', ['mark-as-paid','partner_payout_uuid' => $model->partner_payout_uuid] ,[
                                        'title' => 'Mark as Paid',
                                        'data-pjax' => '0',
                                      ]
                            );
                          }

                      }
                    ],
                ],

          ]


        ]);
        ?>

    </div>

</div>
