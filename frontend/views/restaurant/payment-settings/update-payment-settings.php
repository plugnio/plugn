<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\time\TimePicker;
use common\models\Agent;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use common\models\Area;
use common\models\RestaurantDelivery;
use common\models\RestaurantPaymentMethod;
use common\models\PaymentMethod;
use kartik\file\FileInput;
use common\models\Restaurant;


$this->params['restaurant_uuid'] = $model->restaurant_uuid;

$this->title = 'Update payment settings';
$this->params['breadcrumbs'][] = ['label' => 'Payment Settings', 'url' => ['view-payment-settings','restaurantUuid' =>$model->restaurant_uuid]];
$this->params['breadcrumbs'][] = 'Update payment Settings';

?>

<div class="restaurant-form card">

    <?php

    if($model->business_id && $model->business_entity_id && $model->live_api_key) {
      $paymentMethodQuery = PaymentMethod::find()->asArray()->all();
      $paymentMethodArray = ArrayHelper::map($paymentMethodQuery, 'payment_method_id', 'payment_method_name');
    } else {
      $paymentMethodQuery = PaymentMethod::find()->where(['payment_method_id' => 3])->asArray()->all();
      $paymentMethodArray = ArrayHelper::map($paymentMethodQuery, 'payment_method_id', 'payment_method_name');
    }

    $sotredRestaurantPaymentMethod = [];

    if ($model->restaurant_uuid != null) {


        $sotredRestaurantPaymentMethod = RestaurantPaymentMethod::find()
                ->select('payment_method_id')
                ->asArray()
                ->where(['restaurant_uuid' => $model->restaurant_uuid])
                ->all();

        $sotredRestaurantPaymentMethod = ArrayHelper::getColumn($sotredRestaurantPaymentMethod, 'payment_method_id');
    }


    $form = ActiveForm::begin([
                'id' => 'dynamic-form',
                'errorSummaryCssClass' => 'alert alert-danger'
    ]);
    ?>


    <div class="card-header">
      <h3>Payment Methods</h3>
    </div>
    <div class="card-body">

        <?= $form->errorSummary([$model], ['header' => '<h4 class="alert-heading">Please fix the following errors:</h4>']); ?>

      <?php
          echo $form->field($model, 'restaurant_payments_method')->dropDownList(
            $paymentMethodArray, [
              'class' => 'select2',
              'multiple' => 'multiple',
              'value' => $sotredRestaurantPaymentMethod
            ]
          );
      ?>

        <div class="form-group" style="background: #f4f6f9; margin-bottom: 0px; background:#f4f6f9 ">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width: 100%;height: 50px;']) ?>
        </div>

        <?php ActiveForm::end(); ?>

</div>
