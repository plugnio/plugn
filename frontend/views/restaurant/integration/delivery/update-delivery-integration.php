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

$this->title = 'Update delivery integration';
$this->params['breadcrumbs'][] = ['label' => 'Delivery integration', 'url' => ['view-delivery-integration','restaurantUuid' =>$model->restaurant_uuid]];
$this->params['breadcrumbs'][] = 'Update Delivery integration';

?>

<div class="restaurant-form card">

    <?php

    $form = ActiveForm::begin([
                'id' => 'dynamic-form',
                'errorSummaryCssClass' => 'alert alert-danger'
    ]);
    ?>


    <div class="card-body">

        <?= $form->errorSummary([$model], ['header' => '<h4 class="alert-heading">Please fix the following errors:</h4>']); ?>


        <div class="row">
            <div class="col-12">
              <?= $form->field($model, 'armada_api_key')->textInput(['maxlength' => true]) ?>
            </div>
          </div>
    <div class="row">

            <div class="col-12 col-sm-6 col-lg-6">
              <?= $form->field($model, 'mashkor_api_key')->textInput(['maxlength' => true]) ?>
            </div>

            <div class="col-12 col-sm-6 col-lg-6">
              <?= $form->field($model, 'mashkor_branch_id')->textInput(['maxlength' => true]) ?>
            </div>

          </div>


            <div class="form-group" style="background: #f4f6f9; margin-bottom: 0px;background:#f4f6f9 ">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width: 100%;height: 50px;']) ?>
            </div>

        </div>



        <?php ActiveForm::end(); ?>

</div>
