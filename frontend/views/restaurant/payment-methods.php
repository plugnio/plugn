<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Restaurant;

/* @var $this yii\web\View */
/* @var $model common\models\Restaurant */

$this->params['restaurant_uuid'] = $model->restaurant_uuid;

$this->title = 'Payment Settings';
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="restaurant-view">
  <?php if (Yii::$app->session->getFlash('error') != null) { ?>

      <div class="alert alert-danger alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <h5><i class="icon fa fa-ban"></i> Warning!</h5>
          <?= (Yii::$app->session->getFlash('error')) ?>
      </div>
  <?php } elseif (Yii::$app->session->getFlash('success') != null) { ?>
      <div class="alert alert-success alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <h5><i class="icon fa fa-check"></i> Success!</h5>
          <?= (Yii::$app->session->getFlash('success')) ?>
      </div>
  <?php } ?>

    <!-- Online Payment  -->
    <div class="card">
        <div class="card-header">
            <h3>
                <svg width="24" height="24" fill="none" viewBox="0 0 24 24" class="mr-2"><path fill="#FFB782" d="M22.536 6.82l-4.329-4.337v17.38H24V10.34c0-1.322-.527-2.59-1.464-3.521z"></path><path fill="#DE4C3C" d="M3.383 16.695L.111 8.2c-.328-.853.096-1.81.95-2.14L16.506.112c.853-.328 1.811.097 2.14.95l3.272 8.495c.329.853-.096 1.811-.95 2.14l-15.445 5.95c-.853.328-1.81-.098-2.14-.95z"></path><path fill="#7A4930" d="M19.825 4.121L1.305 11.3l1.234 3.201 18.52-7.175-1.234-3.205z"></path><path fill="#4398D1" d="M0 10.759V1.655C0 .741.741 0 1.655 0h16.552c.914 0 1.655.741 1.655 1.655v9.104c0 .914-.741 1.655-1.655 1.655H1.655C.741 12.414 0 11.673 0 10.759z"></path><path fill="#3E8CC7" d="M18.207 0h-1.759L4.034 12.414h14.173c.914 0 1.655-.741 1.655-1.655V1.655C19.862.741 19.12 0 18.207 0z"></path><path fill="#5EB3D1" d="M1.655 6.62H3.31v.828H1.655v-.827zM1.655 9.103H3.31v.828H1.655v-.828zM9.104 9.103h1.655v.828H9.104v-.828zM4.138 6.62h1.655v.828H4.138v-.827zM6.621 6.62h1.655v.828H6.621v-.827zM9.104 6.62h1.655v.828H9.104v-.827zM16.966 1.655h.827v1.242h-.827V1.655zM15.31 1.655h.828v1.242h-.828V1.655zM13.655 1.655h.828v1.242h-.828V1.655zM12 1.655h.827v1.242H12V1.655z"></path><path fill="#88B337" d="M16.552 19.862H24V24h-7.448v-4.138z"></path><path fill="#FFB782" d="M15.337 7.475c-.701-.7-1.837-.697-2.537.005-.676.678-.7 1.769-.053 2.476l3.391 3.7c-1.02 1.783-.976 3.983.113 5.725l.3.481h5.38V14.07l-6.594-6.594z"></path><path fill="#6B962A" d="M17.793 21.104h.828v.827h-.828v-.828z"></path><path fill="#FDB62F" d="M1.655 4.303V2.317c0-.366.297-.662.662-.662h1.987c.365 0 .662.296.662.662v1.986c0 .366-.297.663-.662.663H2.317c-.365 0-.662-.297-.662-.663z"></path><path fill="#FD7B2F" d="M1.655 2.897h1.242v.827H1.655v-.827zM3.725 2.897h1.24v.827h-1.24v-.827z"></path><path fill="#F2A46F" d="M21.517 14.07c-.11 0-.215-.045-.293-.122l-1.655-1.655c-.159-.165-.154-.427.01-.586.161-.154.415-.154.575 0l1.656 1.656c.161.162.161.423 0 .585-.078.077-.183.121-.293.121z"></path></svg>
                Online Payment
            </h3>

            <?php

              if($model->is_tap_enable)
                echo Html::a($isOnlinePaymentEnabled ?  'Disable' : 'Enable' , [$isOnlinePaymentEnabled ? 'disable-online-payment'  :  'enable-online-payment' , 'restaurantUuid' => $model->restaurant_uuid], ['class' => 'btn btn-success'])

            ?>

        </div>
        <div class="card-body">

            <?php if(!$model->is_tap_enable) { ?>
              <div style="text-align: center;">
                <?php
                if(!$model->tap_queue_id)
                 echo Html::a('Create Tap account' , ['create-tap-account' , 'id' => $model->restaurant_uuid], ['class' => 'btn btn-success']);
                else
                  echo "<h5>Your TAP payments account will be ready within 24 hours. We'll email you once it's ready</h5>";
                 ?>
              </div>
            <?php } else { ?>
            <div class="card-content">

                <p style="color: black;">We partnered with Tap Payments to empower you with competitive fees and shorter settlement windows.</p>

                <div>
                    <div class="row">

                        <div class="col-12">
                            <div class="row">

                              <div class="col-6">
                                  <p style="margin-bottom: 1px;">Company name</p>
                                  <p style="color: black;"><?= $model->company_name ?></p>
                              </div>

                                <div class="col-6">
                                    <p style="margin-bottom: 1px;">Merchant ID</p>
                                    <p style="color: black;"><?= $model->merchant_id ?></p>
                                </div>

                            </div>
                        </div>

                        <div class="col-12">
                            <div class="row">
                                <div class="col-6">
                                    <p style="margin-bottom: 1px;">IBAN</p>
                                    <p style="color: black;"><?= $model->iban ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          <?php } ?>
        </div>

    </div>

    <!-- Cash on Delivery -->
    <div class="card">
        <div class="card-header">
            <h3>
                <svg width="24" height="16" fill="none" viewBox="0 0 24 16" class="mr-2"><rect width="24" height="16" fill="#0E9347" rx="2"></rect><path fill="#3BB54A" fill-rule="evenodd" d="M1 12.455c1.32 0 2.4 1.145 2.4 2.545h17.2c0-1.4 1.08-2.545 2.4-2.545v-8.91c-1.32 0-2.4-1.145-2.4-2.545H3.4c0 1.4-1.08 2.545-2.4 2.545v8.91zM16 8c0 2.21-1.79 4-4 4s-4-1.79-4-4 1.79-4 4-4 4 1.79 4 4zM5 9c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm15-1c0 .552-.448 1-1 1s-1-.448-1-1 .448-1 1-1 1 .448 1 1z" clip-rule="evenodd"></path></svg>
                Cash on Delivery (COD)
            </h3>

            <?= Html::a($isCashOnDeliveryEnabled ?  'Disable' : 'Enable' , [$isCashOnDeliveryEnabled ? 'disable-cod'  :  'enable-cod' , 'restaurantUuid' => $model->restaurant_uuid], ['class' => 'btn btn-success']) ?>

        </div>
        <div class="card-body">
            <div class="card-content">
                <p style="color: black;">
                  Payments that are processed outside your online store. When a customer makes a manual payment, you need to approve their order before fulfilling.
                </p>

            </div>
        </div>

    </div>

</div>
