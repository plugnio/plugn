<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Restaurant;
use common\models\RestaurantPaymentMethod;

/* @var $this yii\web\View */
/* @var $model common\models\Restaurant */

$this->params['restaurant_uuid'] = $model->restaurant_uuid;

$this->title = 'TAP Payments Gateways and rates';
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div style="display:block;     margin-bottom: 10px;">
<?php


      if (!$model->is_tap_enable && !$model->is_myfatoorah_enable) {
                  if (!$model->tap_queue_id && !$model->payment_gateway_queue_id){ ?>
                    <p>
                    Interested?
                    </p>
                    <?php
                    echo Html::a('Create Tap account', ['create-payment-gateway-account', 'id' => $model->restaurant_uuid,'paymentGateway' => 'tap'], ['class' => 'btn btn-success','style'=>'margin-top:10px']);

                  }
      }
?>
</div>

Want better rates?<br/>
<?php
echo Html::a('Upgrade to our premium plan', ['site/confirm-plan', 'id' => $model->restaurant_uuid, 'selectedPlanId' => 2 ], ['style' => 'color: #4CAF50;'])
 ?>

<div class="card">
  <div class="card-header">
      <h3>
        TAP Payments
      </h3>
  </div>

  <div class="card-body">
    <span style="display:block">
      Supported Payment Options
      <br/>
      KNET, Credit Card, Mada and Benefit
    </span>

  <div style="display:block">
    <?php
    echo Html::a('View rates', ['setup-online-payments', 'storeUuid' =>  $model->restaurant_uuid], ['class' => 'btn btn-outline-primary','style'=>'margin-top:10px']);
    ?>
  </div>

  <div style="display:block">
    <?php


          if (!$model->is_tap_enable && !$model->is_myfatoorah_enable) {
                      if (!$model->tap_queue_id && !$model->payment_gateway_queue_id)
                          echo Html::a('Create Tap account', ['create-payment-gateway-account', 'id' => $model->restaurant_uuid,'paymentGateway' => 'tap'], ['class' => 'btn btn-success','style'=>'margin-top:10px']);
          }
    ?>
  </div>

  </div>
</div>

<div class="card">
  <div class="card-header">
      <h3>
        My Fatoorah
      </h3>
  </div>

  <div class="card-body">
    <span style="display:block">
      Supported Payment Options
      <br/>
      KNET, Credit Card, Mada, Sadad and Benefit
    </span>

  <div style="display:block">
    <?php
      echo Html::a('View rates', ['setup-online-payments', 'storeUuid' =>  $model->restaurant_uuid], ['class' => 'btn btn-outline-primary','style'=>'margin-top:10px']);
    ?>
  </div>

  <div style="display:block">
    <?php


          if (!$model->is_tap_enable && !$model->is_myfatoorah_enable) {
              if (!$model->payment_gateway_queue_id)
                  echo Html::a('Create My Fatoorah account', ['create-payment-gateway-account', 'id' => $model->restaurant_uuid,'paymentGateway' => 'myfatoorah'], ['class' => 'btn btn-success','style'=>'margin-top:10px']);
          }


    ?>
  </div>

  </div>
</div>
