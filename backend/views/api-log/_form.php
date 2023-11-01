<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ApiLog */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="api-log-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'log_uuid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'restaurant_uuid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'method')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'endpoint')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'request_headers')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'request_body')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'response_headers')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'response_body')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
