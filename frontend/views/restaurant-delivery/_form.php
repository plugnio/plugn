<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Area;
use yii\helpers\ArrayHelper;
use common\models\RestaurantDelivery;

/* @var $this yii\web\View */
/* @var $model common\models\RestaurantDelivery */
/* @var $form yii\widgets\ActiveForm */


$js = "

$(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

  })

";



$this->registerJs($js);

?>


<div class="card restaurant-delivery-form">
<div class="card-body">

    <?php
    $areaQuery = Area::find()->asArray()->all();
    $restaurantDeliveryArray = ArrayHelper::map($areaQuery, 'area_id', 'area_name');

    if ($model->restaurant_uuid != null) {

        $sotredRestaurantDeliveryAreas = RestaurantDelivery::find()
                ->select('area_id')
                ->asArray()
                ->where(['restaurant_uuid' => $model->restaurant_uuid])
                ->all();

        $sotredRestaurantDeliveryAreas = ArrayHelper::getColumn($sotredRestaurantDeliveryAreas, 'area_id');
    }

    $form = ActiveForm::begin();

    echo $form->errorSummary($model);
    ?>
    <div class="table-responsive">

      <?php
      echo $form->field($model, 'restaurant_delivery_area_array')->dropDownList(
              $restaurantDeliveryArray, [
                'class' => 'form-control select2',
                'multiple' => 'multiple',
                'value' => $sotredRestaurantDeliveryAreas
              ]
          );
      ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
