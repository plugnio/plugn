<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Restaurant;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\Agent */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="agent-form">

    <?php

    $form = ActiveForm::begin();
    ?>

    <?= $form->field($model, 'agent_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'agent_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'agent_email_verification')
        ->checkbox(['checked' => $model->agent_email_verification > 0,  'value' => true]) ?>
<!--
    <?= $form->field($model, 'email_notification')
        ->checkbox(['checked' => $model->email_notification > 0,  'value' => true]) ?>

    <?= $form->field($model, 'reminder_email')
        ->checkbox(['checked' => $model->reminder_email > 0,  'value' => true]) ?>

    <?= $form->field($model, 'receive_weekly_stats')
        ->checkbox(['checked' => $model->receive_weekly_stats > 0,  'value' => true]) ?>
-->
    <?= $form->field($model, 'agent_number')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'agent_phone_country_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ip_address')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'agent_language_pref')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tempPassword')->passwordInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
