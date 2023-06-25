<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

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
                <?= $form->field($model, 'restaurant_uuid') ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'name_ar') ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'platform_fee') ?>
            </div>
            <div class="col-md-3">
            <?= $form->field($model, 'has_deployed')->dropDownList([
                null => 'All',
                1 => 'Yes',
                0 =>'No',
            ]) ?>
            </div>
        </div>
        <div class="row">

            <div class="col-md-4">
                <?php echo $form->field($model, 'noOrder')->checkbox(['label' => 'No order in last 30 days']) ?>
            </div>
            <div class="col-md-4">
                <?php echo $form->field($model, 'noItem')->checkbox(['label' => 'No item added yet']) ?>
            </div>
            <div class="col-md-4">
                <?php echo $form->field($model, 'notActive')->checkbox(['label' => 'In-active for last 30 days']) ?>
            </div>
            <div class="col-md-4">
                <?php echo $form->field($model, 'is_tap_enable')->checkbox([]) ?>
            </div>
            <div class="col-md-4">
                <?php echo $form->field($model, 'is_myfatoorah_enable')->checkbox([]) ?>
            </div>
            <!--
            <div class="col-md-4">
                <?php echo $form->field($model, 'has_deployed')->checkbox([]) ?>
            </div>
            <div class="col-md-4">
                <?php echo $form->field($model, 'has_not_deployed')->checkbox([]) ?>
            </div>-->

            <div class="col-md-4">
                <?php echo $form->field($model, 'is_sandbox')->checkbox([]) ?>
            </div>
            <div class="col-md-4">
                <?php echo $form->field($model, 'is_under_maintenance')->checkbox([]) ?>
            </div>
            <div class="col-md-4">
                <?php echo $form->field($model, 'enable_debugger')->checkbox([
                        'label' => 'Debugger enabled?'
                ]) ?>
            </div>
            <div class="col-md-4">
                <?php echo $form->field($model, 'is_deleted')->checkbox([]) ?>
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
          <?= Html::a('Reset', ['index'], ['class' => 'btn btn-default']) ?>

    </div>

    <?php ActiveForm::end(); ?>

</div>
