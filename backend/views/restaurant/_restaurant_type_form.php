<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$js = "

";
$this->registerJs($js);

/* @var $this yii\web\View */
/* @var $model common\models\Restaurant */
/* @var $merchantTypes common\models\MerchantType */
/* @var $businessTypes common\models\BusinessType */
/* @var $businessCategories common\models\BusinessCategory */
/* @var $form yii\widgets\ActiveForm */
/* @var $restaurantType common\models\RestaurantType */
/* @var $businessItemTypes common\models\BusinessItemType */
/* @var $restaurantItemTypes common\models\RestaurantItemType */


?>

    <div class="restaurant-type-form">

        <?php  $form = ActiveForm::begin([]); ?>

        <?= $form->field($restaurantType, 'business_type_uuid')
            ->dropDownList($businessTypes)->label("Business Type") ?>

        <?= $form->field($restaurantType, 'merchant_type_uuid')
                ->dropDownList($merchantTypes)->label("Merchant Type") ?>

        <?= $form->field($restaurantType, 'business_category_uuid')
            ->dropDownList($businessCategories)->label("Business Category") ?>

        <?php
        //$form->field($restaurantType, 'arrRestaurantItemTypes')
        //    ->dropDownList($businessItemTypes, ['multiple' => true, 'selected' => true])->label("Item Types")
        ?>

        <div class="form-group">
            <label class="control-label">Item Type</label>

            <br />

            <?php foreach ($businessItemTypes as $businessItemType) { ?>
                <?= Html::checkbox('restaurantItemTypes[]',
                    in_array($businessItemType->business_item_type_uuid, $restaurantItemTypes), [
                        'value' => $businessItemType->business_item_type_uuid,
                        'label' => $businessItemType->business_item_type_en]); ?> &nbsp;&nbsp;
            <?php } ?>
        </div>

    <!--
        <table class="table table-hover">
            <tr>
                <th></th>
            </tr>
            <tr>
                <td>
                    <div class="form-group">
                        <label class="control-label">Version</label>
                        <input type="hidden" name="">

                    </div>
                </td>
            </tr>
        </table>-->


        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

