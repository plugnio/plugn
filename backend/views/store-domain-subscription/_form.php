<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\StoreDomainSubscription $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="store-domain-subscription-form">

    <?php $form = ActiveForm::begin(); ?>


    <?php /* \common\components\SelectWidget::widget([
        'action' => 'restaurant/dropdown',
        'form' => $form,
        'formModal' => $model,
        "modalName" => "restaurant",
        'labelAttribute' => "restaurantName",
        'valueAttribute' => "restaurant_uuid",
        "formModalName" => "StoreDomainSubscription"
    ]); */ ?>


    <?= $form->field($model, 'restaurant_uuid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'domain_registrar')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'domain')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'from')->textInput(["type" => "date"]) ?>

    <?= $form->field($model, 'to')->textInput(["type" => "date"]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
