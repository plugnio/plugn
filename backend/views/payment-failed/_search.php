<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\PaymentFailedSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payment-failed-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>


    <div class="grid">
        <div class="row">
            <div class="col-md-3">
                <?= $form->field($model, 'restaurantUuid') ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'customer_id') ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'response') ?>
            </div>

        </div>
    </div>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
       <!--  <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>-->
    </div>

    <?php ActiveForm::end(); ?>

</div>
