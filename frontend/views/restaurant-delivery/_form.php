<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Area;
use yii\helpers\ArrayHelper;
use common\models\RestaurantDelivery;
use softark\duallistbox\DualListbox;

/* @var $this yii\web\View */
/* @var $model common\models\RestaurantDelivery */
/* @var $form yii\widgets\ActiveForm */


?>


<div class="card restaurant-delivery-form">
    <div class="card-body">

        <?php
        $areaQuery = Area::find()->asArray()->all();
        $restaurantDeliveryArray = ArrayHelper::map($areaQuery, 'area_id', 'area_name');


        $form = ActiveForm::begin();

        echo $form->errorSummary($model);
        ?>
        <div class="table-responsive">

            <?php
            $options = [
                'multiple' => true,
                'size' => 20,
            ];

            echo $form->field($model, 'restaurant_delivery_area_array')->widget(DualListbox::className(), [
                'items' => $restaurantDeliveryArray,
                'options' => $options,
                'clientOptions' => [
                    'moveOnSelect' => false,
                    'selectedListLabel' => 'Selected Areas',
                    'nonSelectedListLabel' => 'Available Areas',
                    'infoText' => ''
                ],
            ])->label(false);
            ?>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>


            <?php ActiveForm::end(); ?>

    </div>
</div>
