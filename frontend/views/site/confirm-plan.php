<?php

use common\models\Restaurant;
use yii\helpers\Html;
use common\models\Order;
use common\models\PaymentMethod;
use common\models\AgentAssignment;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */

$this->params['restaurant_uuid'] = $restaurant_model->restaurant_uuid;
$this->title = "Confirm " . $selectedPlan->name . " plan";

?>


<section>

  <div class="card" style="  padding-bottom: 3.2rem;text-align: center;width: 80%;  margin: auto; padding-bottom:2.1rem;">
    <div class="card-header">
      <h4>Payment method</h4>
    </div>

    <div class="card-content">
      <div class="card-body">

        <p style="text-align: left;">Choose how you'd like to pay for Plugn. </p>


        <?php

          $paymentMethodQuery = PaymentMethod::find()->where(['<>','payment_method_id' , '3'])->asArray()->all();
          $paymentMethodArray = ArrayHelper::map($paymentMethodQuery, 'payment_method_id', 'payment_method_name');

            $form = ActiveForm::begin();
        ?>

        <?=
        $form->field($subscription_model, 'payment_method_id')->radioList($paymentMethodArray, [
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
            }
        ])->label(false);
        ?>


        <div class="form-group">
            <?= Html::submitButton('Start plan', ['class' => 'btn btn-success', 'style' => '    float: left;']) ?>
        </div>

        <?php ActiveForm::end();  ?>


      </div>
    </div>
  </div>

</section>
