<?php

use Yii;
use common\models\Restaurant;
use yii\helpers\Html;
use common\models\Order;
use common\models\PaymentMethod;
use common\models\AgentAssignment;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */

$this->params['restaurant_uuid'] = $restaurant->restaurant_uuid;
$this->title = "Confirm " . $selectedPlan->name;

?>


<section>

  <?php if (Yii::$app->session->getFlash('errorResponse') != null) { ?>

    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fa fa-ban"></i> Error!</h5>
        <?= (Yii::$app->session->getFlash('errorResponse')) ?>
    </div>
  <?php } if (Yii::$app->session->hasFlash('error')) { ?>
      <div class="alert alert-danger alert-dismissable">
          <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
          <?php
          foreach(Yii::$app->session->getFlash('error') as $errors) {
              foreach($errors as $error) { ?>
                  <p><?= $error ?></p>
              <?php
              }
          } ?>
      </div>
  <?php }

  <div class="card" style=" padding-bottom:2.1rem;">
    <div class="card-header">
      <h4>Enjoy lower feed on your online payment collection by upgrading your store to our <?= $selectedPlan->name ?></h4>
    </div>

    <div class="card-content">
      <div class="card-body">

        <p style="text-align: left;">Choose how you'd like to pay for Plugn. </p>


        <?php

          $paymentMethodQuery = PaymentMethod::find()->where(['payment_method_id' => '1'])->orWhere(['payment_method_id' => '2'])->asArray()->all();
          $paymentMethodArray = ArrayHelper::map($paymentMethodQuery, 'payment_method_id', 'payment_method_name');

            $form = ActiveForm::begin();
        ?>

        <?=
        $form->field($subscription, 'payment_method_id')->radioList($paymentMethodArray, [
            'style' => 'display:grid',
            'item' => function($index, $label, $name, $checked, $value) {

                $return = '<label class="vs-radio-con">';
                /* -----> */ if ($checked)
                    $return .= '<input checked  type="radio" name="' . $name . '"value="' . $value . '" tabindex="3">';
                /* -----> */
                else
                    $return .= '<input  type="radio" name="' . $name . '"value="' . $value . '" tabindex="3">';
                $return .= '<span class="vs-radio"> <span class="vs-radio--border"></span> <span class="vs-radio--circle"></span> </span>';
                $return .= '<h5>' . ucwords($label) . '</h5>';
                $return .= '</label>';

                return $return;
            },
            'value' => '1'
        ])->label(false);
        ?>


        <div class="form-group">
            <?= Html::submitButton('Make Payment ('.  \Yii::$app->formatter->asCurrency($selectedPlan->price, 'KWD') .')', ['class' => 'btn btn-success', 'style' => '    float: left;']) ?>
        </div>


        <?php ActiveForm::end();  ?>


      </div>
    </div>
  </div>

</section>
