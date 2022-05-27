<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Agent;
use yii\helpers\ArrayHelper;
use common\models\AgentAssignment;

/* @var $this yii\web\View */
/* @var $model common\models\AgentAssignment */
/* @var $form yii\widgets\ActiveForm */



$js = "

$(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })

  })

    $(document).ready(function () {
      
      $('#agentassignment-role').change(function(e) {
      
        const value = $('#agentassignment-role').val();
        
        if(value == ".AgentAssignment::AGENT_ROLE_BRANCH_MANAGER.") {
            $('.field-agentassignment-business_location_id').show();
        } else {
            $('.field-agentassignment-business_location_id').hide();
        }
      });
      
      $('#agentassignment-role').trigger('change');
    });

";


$this->registerJs($js);

?>



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
  <?php } ?>

<div class="card agent-assignment-form">

    <div class="card-body">
    <?php


    $agentQuery = Agent::find()->asArray()->all();
    $agentArray = ArrayHelper::map($agentQuery, 'agent_id', 'agent_name');

    //if(!$model->isNewRecord && $model->role == AgentAssignment::AGENT_ROLE_BRANCH_MANAGER){
      $businessLocationsQuery = $model->restaurant->getBusinessLocations()->asArray()->all();
      $businessLocationsList = ArrayHelper::map($businessLocationsQuery, 'business_location_id', 'business_location_name');
    //}

    $agentValue = [];

    if ($model->agent_id != null) {

        $agentValue = AgentAssignment::find()
                ->select('agent_id')
                ->asArray()
                ->andWhere(['agent_id' => $model->agent_id])
                ->one();
    }

    $form = ActiveForm::begin([
      'errorSummaryCssClass' => 'alert alert-danger'
    ]);

    ?>

    <?= $form->errorSummary($model,['header' => '<h4 class="alert-heading">Please fix the following errors:</h4>']); ?>


    <?php
           if($model->isNewRecord) {
               echo $form->field ($model, 'assignment_agent_email')
                   ->textInput (['maxlength' => true, 'id' => 'agent-email']);

               echo $form->field ($model, 'agent_name')
                   ->textInput (['maxlength' => true, 'id' => 'agent-name']);
           }
    ?>

    <?=
    $form->field($model, 'role')->dropDownList(
            [
        AgentAssignment::AGENT_ROLE_OWNER => "Owner",
        AgentAssignment::AGENT_ROLE_STAFF => "Staff",
        AgentAssignment::AGENT_ROLE_BRANCH_MANAGER => "Branch Manager"
            ], [
        'class' => 'form-control select2 select2',
        'multiple' => false,
    ]);
    ?>

    <?php

     // if(!$model->isNewRecord &&  $model->role == AgentAssignment::AGENT_ROLE_BRANCH_MANAGER) {
      echo $form->field($model, 'business_location_id')->dropDownList(
            $businessLocationsList, [

            'class' => 'form-control select2 select2',
            'multiple' => false,
        ])->label('Managed branch');
     // }

    ?>

    <div class="form-group" style="background: #f4f6f9; margin-bottom: 0px;  background:#f4f6f9 ">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width: 100%;height: 50px;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
