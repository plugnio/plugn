<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Restaurant;

$paymentMethodQuery = \common\models\PaymentMethod::find()->asArray()->all();
$paymentMethodArray = \yii\helpers\ArrayHelper::map($paymentMethodQuery, 'payment_method_id', 'payment_method_name');
$paymentMethodArray = [null => 'All'] + $paymentMethodArray;

/* @var $this yii\web\View */
/* @var $model backend\models\RestaurantSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="restaurant-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="grid">

        <div class="row">
            <div class="col-md-3">
                <?= $form->field($model, 'enable_gift_message')->dropDownList([
                    null => 'All',
                    1 => 'Yes',
                    0 =>'No',
                ]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'accept_order_247')->dropDownList([
                    null => 'All',
                    1 => 'Yes',
                    0 =>'No',
                ]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'store_layout')->dropDownList([
                    null => 'All',
                    Restaurant::STORE_LAYOUT_LIST_FULLWIDTH => "List Full Width",
                    Restaurant::STORE_LAYOUT_GRID_FULLWIDTH => "Grid Full Width",
                    Restaurant::STORE_LAYOUT_CATEGORY_FULLWIDTH => "Category Full Width",
                    Restaurant::STORE_LAYOUT_LIST_HALFWIDTH => "List Half Width",
                    Restaurant::STORE_LAYOUT_GRID_HALFWIDTH =>  "Grid Half Width",
                    Restaurant::STORE_LAYOUT_CATEGORY_HALFWIDTH => "Category Half Width",
                ]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'vendor_sector')->dropDownList([
                    null => 'All',
                    'Shopping & Retail' => 'Shopping & Retail',
                    'F&B' =>'F&B',
                    'Other' => 'Other'
                ]) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <?= $form->field($model, 'retention_email_sent')->dropDownList([
                    null => 'All',
                    1 => 'Yes',
                    0 =>'No',
                ]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'iban') ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'business_id') ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'business_entity_id') ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <?= $form->field($model, 'currency_title') ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'wallet_id') ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'merchant_id') ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'operator_id') ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <?= $form->field($model, 'country_name') ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'license_number') ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'referral_code') ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'is_public')->dropDownList([
                    null => 'All',
                    1 => 'Yes',
                    0 =>'No',
                ]) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <?= $form->field($model, 'restaurant_uuid') ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'name_ar') ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'platform_fee')
                    ->hint("Our commission") ?>
            </div>
            <div class="col-md-3">
            <?= $form->field($model, 'has_deployed')
                ->hint("Deployed to netlify")
                ->dropDownList([
                null => 'All',
                1 => 'Yes',
                0 =>'No',
            ]) ?>
            </div>
        </div>
        <div class="row">

            <div class="col-md-3">
                <?= $form->field($model, 'ip_address') ?>
            </div>

            <div class="col-md-3">
                <?= $form->field($model, 'payment_method_id')
                    ->label("Payment Method")
                    ->dropDownList($paymentMethodArray); ?>
            </div>

            <div class="col-md-3">
                <?php echo $form->field($model, 'noOrder')->checkbox(['label' => 'No order in last 30 days']) ?>
            </div>

            <div class="col-md-3">
                <?php echo $form->field($model, 'noItem')
                    ->checkbox(['label' => 'No item added yet']) ?>
            </div>

            <div class="col-md-3">
                <?php echo $form->field($model, 'notActive')
                    ->hint("No order and items")
                    ->checkbox(['label' => 'In-active']) ?>
            </div>

            <div class="col-md-3">
                <?php echo $form->field($model, 'active')
                    ->hint("Having order and/or items")
                    ->checkbox(['label' => 'Active stores']) ?>
            </div>

            <div class="col-md-3">
                <?php echo $form->field($model, 'notActive90Days')
                    ->hint("Dashboard not accessed in 90 days")
                    ->checkbox(['label' => 'In-active for last 90 days']) ?>
            </div>
            <div class="col-md-3">
                <?php echo $form->field($model, 'is_tap_enable')
                    ->hint("Tap payment enabled")
                    ->checkbox([]) ?>
            </div>
            <div class="col-md-3">
                <?php echo $form->field($model, 'is_myfatoorah_enable')
                    ->hint("Myfatoorah delivery enabled")
                    ->checkbox([]) ?>
            </div>
            <div class="col-md-3">
                <?php echo $form->field($model, 'customDomain')
                    ->hint("With custom domain/ url")
                    ->checkbox([]) ?>
            </div>
            <!--
            <div class="col-md-3">
                <?php echo $form->field($model, 'has_deployed')
                    ->hint("Hosted on netlify")
                    ->checkbox([]) ?>
            </div>
            <div class="col-md-3">
                <?php echo $form->field($model, 'has_not_deployed')
                    ->hint("Not hosted on netlify")
                    ->checkbox([]) ?>
            </div>-->

            <div class="col-md-3">
                <?php echo $form->field($model, 'is_sandbox')
                    ->hint("Store under testing")
                    ->checkbox([]) ?>
            </div>

            <div class="col-md-3">
                <?php echo $form->field($model, 'is_under_maintenance')
                    ->hint("Store flagged under maintenance")
                    ->checkbox([]) ?>
            </div>
            <div class="col-md-3">
                <?php echo $form->field($model, 'enable_debugger')->checkbox([
                        'label' => 'Debugger enabled?'
                ]) ?>
            </div>
            <div class="col-md-3">
                <?php echo $form->field($model, 'is_deleted')
                    ->hint("Deleted by user or for inactivity")
                    ->checkbox([]) ?>
            </div>

            <div class="col-md-3">
                <?php echo $form->field($model, 'notDeleted')
                    ->hint("Store not deleted")
                    ->checkbox([]) ?>
            </div>

            <div class="col-md-3">
                <?php echo $form->field($model, 'activeSubscription')
                    ->hint("Having premium plan")
                    ->checkbox([]) ?>
            </div>

            <div class="col-md-3">
                <?php echo $form->field($model, 'noActiveSubscription')
                    ->hint("No premium plan")
                    ->checkbox([]) ?>
            </div>

        </div>
    </div>

    <?php // echo $form->field($model, 'tagline_ar') ?>

    <?php //echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'thumbnail_image') ?>

    <?php // echo $form->field($model, 'logo') ?>

    <?php // echo $form->field($model, 'support_delivery') ?>

    <?php // echo $form->field($model, 'support_pick_up') ?>

    <?php // echo $form->field($model, 'min_charge') ?>

    <?php // echo $form->field($model, 'phone_number') ?>

    <?php // echo $form->field($model, 'restaurant_created_at') ?>

    <?php // echo $form->field($model, 'restaurant_updated_at') ?>



    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>

        <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-default']) ?>

    </div>

    <?php ActiveForm::end(); ?>

</div>
