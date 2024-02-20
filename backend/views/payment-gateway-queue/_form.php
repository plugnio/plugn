\<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\PaymentGatewayQueue;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\PaymentGatewayQueue */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payment-gateway-queue-form">

  <?php

  //$restaurantQuery = Restaurant::find()->asArray()->all();
  //$restaurantArray = ArrayHelper::map($restaurantQuery, 'restaurant_uuid', 'name');

  $form = ActiveForm::begin();
  ?>

    <?php /*
        $form->field($model, 'restaurant_uuid')->widget(Select2::classname(), [
            'data' => $restaurantArray,
            'options' => ['placeholder' => 'Select a restaurant ...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label('Restaurant');*/
    ?>

    <?= $form->field($model, 'restaurant_uuid')->textInput() ?>

    <?= $form->field($model, 'payment_gateway')->dropDownList(
        [
          'myfatoorah' => 'MyFatoorah',
          'tap' => 'Tap',
        ]);
   ?>

    <?= $form->field($model, 'queue_status')->dropDownList(
        [
            PaymentGatewayQueue:: QUEUE_STATUS_PENDING => 'Pending',
            PaymentGatewayQueue:: QUEUE_STATUS_CREATING => 'Creating',
            PaymentGatewayQueue:: QUEUE_STATUS_COMPLETE => 'Complete',
            PaymentGatewayQueue:: QUEUE_STATUS_FAILED => 'Failed'
        ]
    ); ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
