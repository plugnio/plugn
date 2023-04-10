<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\RestaurantDomainRequest */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="restaurant-domain-request-form">

    <?php $form = ActiveForm::begin(); ?>

    <?=  \common\components\SelectWidget::widget([
        'action' => 'restaurant/dropdown',
        'form' => $form,
        'formModal' => $model,
        "modalName" => "restaurant",
        'labelAttribute' => "restaurantname",
        'valueAttribute' => "restaurant_uuid",
        "formModalName" => "restaurantdomainrequest"
    ]); ?>

    <?= $form->field($model, 'domain')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList(\common\models\RestaurantDomainRequest::arrStatus()) ?>

    <?= $form->field($model, 'expire_at')->widget(\kartik\date\DatePicker::classname(), [
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ]
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
