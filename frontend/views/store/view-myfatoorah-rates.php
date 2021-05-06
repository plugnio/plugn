<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Restaurant;
use common\models\RestaurantPaymentMethod;

/* @var $this yii\web\View */
/* @var $model common\models\Restaurant */

$this->params['restaurant_uuid'] = $model->restaurant_uuid;

$this->title = 'My Fatoorah Gateways and rates';
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div style="display:block;     margin-bottom: 10px;">
<?php


      if (!$model->is_tap_enable && !$model->is_myfatoorah_enable) {
          if (!$model->payment_gateway_queue_id)
              echo Html::a('Create My Fatoorah account', ['create-payment-gateway-account', 'id' => $model->restaurant_uuid,'paymentGateway' => 'myfatoorah'], ['class' => 'btn btn-success','style'=>'margin-top:10px']);
      }

?>
</div>


<!-- Credit Card -->
<div class="card">
  <div class="card-header">
      <h3>
        Credit Card
      </h3>
      <div style="text-align: center; display:block">
        <img src="<?= Yii::$app->urlManager->getBaseUrl() . '/img/master-card.svg' ?>" style="width: 50px;">
        <img src="<?= Yii::$app->urlManager->getBaseUrl() . '/img/visa.svg' ?>" style="width: 50px;">
      </div>
  </div>

  <div class="card-body row">
    <div class="col-12 col-sm-4 col-lg-4">
      <!-- Settlement window -->
      <span style="display:block">
        <h5><b>Settlement window</b></h5>
        <span>
          Within 5 working days
        </span>
      </span>
    </div>


    <div class="col-12 col-sm-4 col-lg-4">
      <!-- Fees (on premium plan) -->
      <span >
        <h5><b>Fees (on premium plan)</b></h5>
        <span>
            2.5% per transaction, no minimum
        </span>
      </span>
    </div>

    <div class="col-12 col-sm-4 col-lg-4">
      <!-- Fees (on free plan) -->
      <span >
        <h5><b>Fees (on free plan)</b></h5>
        <span>
          5% per transaction, no minimum
        </span>
      </span>
    </div>


  </div>
</div>

<!-- Knet -->
<div class="card">

  <div class="card-header">
      <h3>
        Knet
      </h3>
      <div style="text-align: center; display:block">
        <img src="<?= Yii::$app->urlManager->getBaseUrl() . '/img/knet.svg' ?>" style="width: 50px;">
      </div>
  </div>




  <div class="card-body row">

    <div class="col-12 col-sm-4 col-lg-4">
      <!-- Settlement window -->
      <span style="display:block">
        <h5><b>Settlement window</b></h5>
        <span>
          Within 3 working days
        </span>
      </span>
    </div>

    <div class="col-12 col-sm-4 col-lg-4">
      <!-- Fees (on premium plan) -->
      <span >
        <h5><b>Fees (on premium plan)</b></h5>
        <span>
          150 fils per transaction, no minimum
        </span>
      </span>
    </div>

    <div class="col-12 col-sm-4 col-lg-4">

      <!-- Fees (on free plan) -->
      <span >
        <h5><b>Fees (on free plan)</b></h5>
        <span>
          5% per transaction, a minimum of 250 fils
        </span>
      </span>

    </div>


  </div>
</div>

<!-- Benefit -->
<div class="card">
  <div class="card-header">
      <h3>
        Benefit
      </h3>
      <div style="text-align: center; display:block">
        <img src="<?= Yii::$app->urlManager->getBaseUrl() . '/img/benefit.png' ?>" style="width: 50px;">
      </div>
  </div>

  <div class="card-body row">
    <div class="col-12 col-sm-4 col-lg-4">
      <!-- Settlement window -->
      <span style="display:block">
        <h5><b>Settlement window</b></h5>
        <span>
          Within 3 working days
        </span>
      </span>
    </div>


    <div class="col-12 col-sm-4 col-lg-4">
      <!-- Fees (on premium plan) -->
      <span >
        <h5><b>Fees (on premium plan)</b></h5>
        <span>
            1.25% per transaction, no minimum
        </span>
      </span>
    </div>

    <div class="col-12 col-sm-4 col-lg-4">
      <!-- Fees (on free plan) -->
      <span >
        <h5><b>Fees (on free plan)</b></h5>
        <span>
          5% per transaction, no minimum
        </span>
      </span>
    </div>


  </div>
</div>

<!-- Mada -->
<div class="card">
  <div class="card-header">
      <h3>
        Mada
      </h3>
      <div style="text-align: center; display:block">
        <img src="<?= Yii::$app->urlManager->getBaseUrl() . '/img/mada.svg' ?>" style="width: 50px;">
      </div>
  </div>

  <div class="card-body row">
    <div class="col-12 col-sm-4 col-lg-4">
      <!-- Settlement window -->
      <span style="display:block">
        <h5><b>Settlement window</b></h5>
        <span>
          Within 3 working days
        </span>
      </span>
    </div>

    <div class="col-12 col-sm-4 col-lg-4">
      <!-- Fees (on premium plan) -->
      <span >
        <h5><b>Fees (on premium plan)</b></h5>
        <span>
            1.5% per transaction, no minimum
        </span>
      </span>
    </div>

    <div class="col-12 col-sm-4 col-lg-4">
      <!-- Fees (on free plan) -->
      <span >
        <h5><b>Fees (on free plan)</b></h5>
        <span>
          5% per transaction, no minimum
        </span>
      </span>
    </div>

  </div>
</div>

<!-- Sadad -->
<div class="card" style="margin-top:10p">
  <div class="card-header">
      <h3>
        Sadad
      </h3>
      <div style="text-align: center; display:block">
        <img src="<?= Yii::$app->urlManager->getBaseUrl() . '/img/sadad.png' ?>" style="width: 50px;">
      </div>
  </div>

  <div class="card-body row">
    <div class="col-12 col-sm-4 col-lg-4">

      <!-- Settlement window -->
        <span style="display:block">
          <h5><b>Settlement window</b></h5>
          <span>
            Within 3 working days
          </span>
        </span>

      </div>

      <div class="col-12 col-sm-4 col-lg-4">

    <!-- Fees (on premium plan) -->
    <span style="display:block;margin-top:10px">
      <h5><b>Fees (on premium plan)</b></h5>
      <span>
          2.5% per transaction, no minimum
      </span>
    </span>
  </div>

    <div class="col-12 col-sm-4 col-lg-4">

    <!-- Fees (on free plan) -->
    <span style="display:block;margin-top:10px">
      <h5><b>Fees (on free plan)</b></h5>
      <span>
        5% per transaction, no minimum
      </span>
    </span>
  </div>


  </div>
</div>
