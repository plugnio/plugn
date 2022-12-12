<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Restaurant;
use common\models\Plan;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\Subscription */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="subscription-form">

    <?php

    $planQuery = Plan::find()->asArray()->all();
    $planArray = ArrayHelper::map($planQuery, 'plan_id', 'name');

    $form = ActiveForm::begin();
    ?>

    <?=  \common\components\SelectWidget::widget([
        'action' => 'restaurant/dropdown',
        'form' => $form,
        'formModal' => $model,
        "modalName" => "restaurant",
        'labelAttribute' => "restaurantname",
        'valueAttribute' => "restaurant_uuid",
        "formModalName" => "subscription"
    ]); ?>

    <?=
        $form->field($model, 'plan_id')->widget(Select2::classname(), [
            'data' => $planArray,
            'options' => ['placeholder' => 'Select a restaurant ...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label('Plan');
    ?>

    <?=
        $form->field($model, 'subscription_status')->widget(Select2::classname(), [
            'data' => [
              10 => 'Active',
              0 => 'Inactive'
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
    ?>

    <?= $form->field($model, 'subscription_start_at')->textInput(['type' => 'datetime']) ?>

    <?= $form->field($model, 'subscription_end_at')->textInput(['type' => 'datetime']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
