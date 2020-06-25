<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\models\Agent;
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
      bsCustomFileInput.init();
    });

";


$this->registerJs($js);
?>



<div class="agent-assignment-form">

    <?php


    $agentQuery = Agent::find()->asArray()->all();
    $agentArray = ArrayHelper::map($agentQuery, 'agent_id', 'agent_name');


    $agentValue = [];

    if ($model->agent_id != null) {

        $agentValue = AgentAssignment::find()
                ->select('agent_id')
                ->asArray()
                ->where(['agent_id' => $model->agent_id])
                ->one();
    }

    $form = ActiveForm::begin(['id' => 'agent']);
    ?>

    <?= $form->errorSummary($model); ?>


    <?php
           if($model->isNewRecord)
             echo $form->field($model, 'assignment_agent_email')->textInput(['maxlength' => true , 'id' =>'agent-email'])
    ?>

    <?=
    $form->field($model, 'role')->dropDownList(
            [
        AgentAssignment::AGENT_ROLE_OWNER => "Owner",
        AgentAssignment::AGENT_ROLE_STAFF => "Staff"
            ], [
        'class' => 'form-control select2 select2',
        'multiple' => false,
    ]);
    ?>

    <div class="form-group" style="background: #f4f6f9; padding-bottom: 10px; margin-bottom: 0px; padding-bottom: 15px; background:#f4f6f9 ">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width: 100%;height: 50px;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
