<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\StoreDomainSubscriptionPaymentSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="store-domain-subscription-payment-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'store_domain_subscription_payment_uuid') ?>

    <?= $form->field($model, 'subscription_uuid') ?>

    <?= $form->field($model, 'from') ?>

    <?= $form->field($model, 'to') ?>

    <?= $form->field($model, 'total_amount') ?>

    <?php // echo $form->field($model, 'cost_amount') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
