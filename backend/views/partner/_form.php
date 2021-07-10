<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Partner */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="partner-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'partner_uuid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'partner_auth_key')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'partner_password_hash')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'partner_password_reset_token')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'partner_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'partner_status')->textInput() ?>

    <?= $form->field($model, 'referral_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'commission')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'partner_created_at')->textInput() ?>

    <?= $form->field($model, 'partner_updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
