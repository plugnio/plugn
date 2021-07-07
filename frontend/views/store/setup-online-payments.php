<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Restaurant;
use common\models\RestaurantPaymentMethod;

/* @var $this yii\web\View */
/* @var $model common\models\Restaurant */

$this->params['restaurant_uuid'] = $model->restaurant_uuid;

$this->title = 'Online Payments';
$this->params['breadcrumbs'][] = ['label' => 'Payment Settings', 'url' => ['view-payment-methods', 'storeUuid' => $model->restaurant_uuid]];
$this->params['breadcrumbs'][] = $this->title;

\yii\web\YiiAsset::register($this);
?>

<p>
  We have payments from the following providers, check out the rates charged on each online transaction.
  <br/>
  <br/>

You can only connect one of the two. Choose wisely.

</p>


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
    echo Html::a('View rates', ['view-tap-rates', 'storeUuid' =>  $model->restaurant_uuid], ['class' => 'btn btn-outline-primary','style'=>'margin-top:10px']);
    ?>
  </div>

  <div style="display:block">
    <?php

          if (!$model->is_tap_enable && !$model->is_myfatoorah_enable) {
                echo Html::a('Create Tap account', ['create-payment-gateway-account', 'id' => $model->restaurant_uuid,'paymentGateway' => 'tap'], ['class' => 'btn btn-success','style'=>'margin-top:10px']);
          }
    ?>
  </div>

  </div>
</div>

<?php if ( $model->country->iso != 'BH' && $model->currency->code != 'BHD' ){ ?>
    <div class="card">
      <div class="card-header">
          <h3>
            MyFatoorah
          </h3>
      </div>

      <div class="card-body">
        <span style="display:block">
          Supported Payment Options
          <br/>
          KNET, Credit Card, Mada and Sadad
        </span>

      <div style="display:block">
        <?php
          echo Html::a('View rates', ['view-myfatoorah-rates', 'storeUuid' =>  $model->restaurant_uuid], ['class' => 'btn btn-outline-primary','style'=>'margin-top:10px']);
        ?>
      </div>

      <div style="display:block">
        <?php


              if (!$model->is_tap_enable && !$model->is_myfatoorah_enable) {
                      echo Html::a('Create MyFatoorah account', ['create-payment-gateway-account', 'id' => $model->restaurant_uuid,'paymentGateway' => 'myfatoorah'], ['class' => 'btn btn-success','style'=>'margin-top:10px']);
              }

        ?>
      </div>

      </div>
    </div>
<?php } ?>
