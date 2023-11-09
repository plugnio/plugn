<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\RestaurantType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="restaurant-type-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'restaurant_type_uuid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'restaurant_uuid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'merchant_type_uuid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'business_type_uuid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'business_category_uuid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
