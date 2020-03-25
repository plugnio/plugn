<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\RestaurantDelivery */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="restaurant-delivery-form">


    <?php
    $areaQuery = Area::find()->asArray()->all();
    $restaurantDeliveryArray = ArrayHelper::map($areaQuery, 'area_id', 'area_name');

    if ($restaurant_model->restaurant_uuid != null) {

        $sotredRestaurantDeliveryAreas = RestaurantDelivery::find()
                ->select('area_id')
                ->asArray()
                ->where(['restaurant_uuid' => $restaurant_model->restaurant_uuid])
                ->all();

        $sotredRestaurantDeliveryAreas = ArrayHelper::getColumn($sotredRestaurantDeliveryAreas, 'area_id');
    }

    $form = ActiveForm::begin();

    echo $form->errorSummary($restaurant_model);

    echo $form->field($restaurant_model, 'restaurant_delivery_area')->dropDownList(
            $restaurantDeliveryArray, [
        'class' => 'select2',
        'multiple' => 'multiple',
        'value' => $sotredRestaurantDeliveryAreas
            ]
    );
    ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
