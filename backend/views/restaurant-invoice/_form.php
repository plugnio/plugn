<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\components\SelectWidget;

$arrCurrencies = \agent\models\Currency::find()->all();

$currencies = \yii\helpers\ArrayHelper::map($arrCurrencies, 'code', 'code');
/* @var $this yii\web\View */
/* @var $model common\models\RestaurantInvoice */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="restaurant-invoice-form">

    <?php $form = ActiveForm::begin(); ?>

    <?=  SelectWidget::widget([
        'action' => 'restaurant/dropdown',
        'form' => $form,
        'formModal' => $model,
        "modalName" => "restaurant",
        'labelAttribute' => "restaurantname",
        'valueAttribute' => "restaurant_uuid",
        "formModalName" => "restaurantinvoice"
    ]); ?>

    <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'currency_code')->dropDownList($currencies) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
