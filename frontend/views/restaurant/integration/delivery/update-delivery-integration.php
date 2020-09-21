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

$this->title = 'Delivery integration';
$this->params['breadcrumbs'][] = 'Delivery Integration';

$form = ActiveForm::begin([
            'id' => 'dynamic-form',
            'errorSummaryCssClass' => 'alert alert-danger'
]);

?>

<?= $form->errorSummary([$model], ['header' => '<h4 class="alert-heading">Please fix the following errors:</h4>']); ?>

<div class="row">
  <div class="col-12 col-sm-6 col-lg-6">

          <div class="card">
              <div class="card-content">
                  <div class="card-body" style="padding-bottom: 0px;">
                      <h4 class="card-title">Mashkor Delivery</h4>
                      <a class="mb-4 text-primary-base hover:text-primary-700" rel="noopener noreferrer" target="_blank" href="https://www.plugn.io/local-delivery/mashkor">
                        <span>Learn more about Mashkor Delivery
                          <svg width="24" height="24" viewBox="0 0 24 24" class="inline"><g fill="none" fill-rule="evenodd"><path d="M0 0h24v24H0z"></path><path fill="#3852CA" fill-rule="nonzero" d="M7.333 7.2h4a.8.8 0 0 1 .1 1.594l-.1.006h-4a.488.488 0 0 0-.377.156.481.481 0 0 0-.15.289l-.006.088v7.334c0 .412.074.518.436.531l.097.002h7.334c.412 0 .518-.074.531-.436l.002-.097v-4a.8.8 0 0 1 1.594-.1l.006.1v4c0 1.273-.734 2.062-1.963 2.128l-.17.005H7.333c-1.273 0-2.062-.734-2.128-1.963l-.005-.17V9.333c0-.58.214-1.098.625-1.508a2.077 2.077 0 0 1 1.317-.617l.191-.008h4-4zM14 5.2h4.029l.052.004L18 5.2a.805.805 0 0 1 .566.234l-.077-.067a.804.804 0 0 1 .305.533l.002.017a.805.805 0 0 1 .004.065V10a.8.8 0 0 1-1.594.1L17.2 10l-.001-2.069-5.967 5.968a.8.8 0 0 1-1.041.077l-.09-.077a.8.8 0 0 1 0-1.131l5.968-5.969L14 6.8a.8.8 0 0 1-.1-1.594L14 5.2h4-4z"></path></g>
                          </svg>
                        </span>

                      </a>

                  </div>

                  <div class="card-body">
                      <form class="form">
                          <div class="form-body">
                            <?= $form->field($model, 'mashkor_branch_id',[
                              'labelOptions' => [ 'style' => 'font-size: 0.875rem; '],
                              'options' => ['style' => 'margin-bottom:  0px;'],
                            ]
                          )->textInput(['maxlength' => true, 'style' => 'margin-bottom:  0px;','placeholder' => 'Mashkor Branch id']) ?>
                          </div>
                  </div>

              </div>
          </div>

    </div>

    <div class="col-12 col-sm-6 col-lg-6">

            <div class="card">
                <div class="card-content">
                    <div class="card-body" style="padding-bottom: 0px;">
                        <h4 class="card-title">Armada Delivery</h4>
                        <a class="mb-4 text-primary-base hover:text-primary-700" rel="noopener noreferrer" target="_blank" href="https://www.plugn.io/local-delivery/armada">
                          <span class="block w-full inline" style="direction: ltr;">Learn more about Armada Delivery
                            <svg width="24" height="24" viewBox="0 0 24 24" class="inline"><g fill="none" fill-rule="evenodd"><path d="M0 0h24v24H0z"></path><path fill="#3852CA" fill-rule="nonzero" d="M7.333 7.2h4a.8.8 0 0 1 .1 1.594l-.1.006h-4a.488.488 0 0 0-.377.156.481.481 0 0 0-.15.289l-.006.088v7.334c0 .412.074.518.436.531l.097.002h7.334c.412 0 .518-.074.531-.436l.002-.097v-4a.8.8 0 0 1 1.594-.1l.006.1v4c0 1.273-.734 2.062-1.963 2.128l-.17.005H7.333c-1.273 0-2.062-.734-2.128-1.963l-.005-.17V9.333c0-.58.214-1.098.625-1.508a2.077 2.077 0 0 1 1.317-.617l.191-.008h4-4zM14 5.2h4.029l.052.004L18 5.2a.805.805 0 0 1 .566.234l-.077-.067a.804.804 0 0 1 .305.533l.002.017a.805.805 0 0 1 .004.065V10a.8.8 0 0 1-1.594.1L17.2 10l-.001-2.069-5.967 5.968a.8.8 0 0 1-1.041.077l-.09-.077a.8.8 0 0 1 0-1.131l5.968-5.969L14 6.8a.8.8 0 0 1-.1-1.594L14 5.2h4-4z"></path></g>
                            </svg>
                          </span>

                        </a>

                    </div>
                    <div class="card-body">
                        <form class="form">
                            <div class="form-body">
                              <?= $form->field($model, 'armada_api_key',[
                                'labelOptions' => [ 'style' => 'font-size: 0.875rem; '],
                                'options' => ['style' => 'margin-bottom:  0px;'],
                              ]
                            )->textInput(['maxlength' => true, 'style' => 'margin-bottom:  0px;','placeholder' => 'Armada Api Key']) ?>
                            </div>
                    </div>
                </div>
            </div>

      </div>

  </div>

  <div class="form-group" style="background: #f4f6f9; margin-bottom: 0px;background:#f4f6f9; ">
      <?= Html::submitButton('Save Changes', ['class' => 'btn btn-success', 'style' => 'font-size: 16px;font-weight: 600; height: 2.5rem; padding: 0px 1.5rem;']) ?>
  </div>


        <?php ActiveForm::end(); ?>
