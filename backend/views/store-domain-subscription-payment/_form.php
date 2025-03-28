<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\StoreDomainSubscriptionPayment $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="store-domain-subscription-payment-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'from')->textInput() ?>

    <?= $form->field($model, 'to')->textInput() ?>

    <?= $form->field($model, 'total_amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cost_amount')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
