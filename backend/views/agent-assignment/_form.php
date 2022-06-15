<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Agent;
use common\models\Restaurant;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use common\models\AgentAssignment;


/* @var $this yii\web\View */
/* @var $model common\models\AgentAssignment */
/* @var $form yii\widgets\ActiveForm */


$js = "
let supportDeliveryInput = $('#supportDeliveryInput');
let supportPickupInput = $('#supportPickupInput');

// On Change of project type input
supportDeliveryInput.change(function(){
    let selection = $(this).val();
    if(selection == 0){ // Dont support delivery
        $('#minDeliveryTime').hide();
    }else{ // Support delivery
        $('#minDeliveryTime').show();
    }
});

supportPickupInput.change(function(){
    let selection = $(this).val();
    if(selection == 0){ // Dont support pickup
        $('#minPickupTime').hide();
    }else{ // Reward based
        $('#minPickupTime').show();
    }
});


";


$this->registerJs($js);
?>

<div class="agent-assignment-form">

    <?php
    $agentQuery = Agent::find()->asArray()->all();
    $agentArray = ArrayHelper::map($agentQuery, 'agent_id', 'agent_name');

    $restaurantQuery = Restaurant::find()->asArray()->all();
    $restaurantArray = ArrayHelper::map($restaurantQuery, 'restaurant_uuid', 'name');

    if(!$model->isNewRecord && $model->role == AgentAssignment::AGENT_ROLE_BRANCH_MANAGER){
      $businessLocationsQuery = $model->restaurant->getBusinessLocations()->asArray()->all();
      $businessLocationsList = ArrayHelper::map($businessLocationsQuery, 'business_location_id', 'business_location_name');
    }

    $form = ActiveForm::begin();
    ?>

    <?=
        $form->field($model, 'agent_id')->widget(Select2::classname(), [
            'data' => $agentArray,
            'options' => ['placeholder' => 'Select a agent ...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label('Agent');
    ?>

    <?=
        $form->field($model, 'restaurant_uuid')->widget(Select2::classname(), [
            'data' => $restaurantArray,
            'options' => ['placeholder' => 'Select a restaurant ...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label('Restaurant');
    ?>



    <?=
        $form->field($model, 'role')->widget(Select2::classname(), [
            'data' => [
                AgentAssignment::AGENT_ROLE_OWNER => "Owner",
                AgentAssignment::AGENT_ROLE_STAFF => "Staff",
                AgentAssignment::AGENT_ROLE_BRANCH_MANAGER => "Branch Manager"
            ],
            'options' => ['placeholder' => 'Select agents role ...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label('Role');
    ?>

    <?php

      if(!$model->isNewRecord && $model->role == AgentAssignment::AGENT_ROLE_BRANCH_MANAGER){
        echo $form->field($model, 'business_location_id')->widget(Select2::classname(), [
            'data' => $businessLocationsList,
            'options' => ['placeholder' => 'Select a branch ...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label('Managed branch');
      }

    ?>

    <?=
      $form->field($model, 'receive_weekly_stats')->dropDownList(
              [
          1 => 'Yes',
          0 => 'No',
              ]
      );
    ?>
    <?=
      $form->field($model, 'email_notification')->dropDownList(
              [
          1 => 'Yes',
          0 => 'No',
              ]
      );
    ?>

    <?=
      $form->field($model, 'reminder_email')->dropDownList(
              [
          1 => 'Yes',
          0 => 'No',
              ]
      );
    ?>



    <div class="form-group">
    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

        <?php ActiveForm::end(); ?>

</div>
