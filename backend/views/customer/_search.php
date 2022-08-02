<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var $model frontend\models\CustomerSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="card">



    <div class="card-header" style="padding-bottom:21px !important">
        <h4 class="card-title">Filters</h4>
        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
        <div class="heading-elements">
            <ul class="list-inline mb-0">
                <li><a data-action="collapse"><i class="feather icon-chevron-down"></i></a></li>
                <li><a data-action="close"><i class="feather icon-x"></i></a></li>
            </ul>
        </div>
    </div>
    <div class="card-content  ">


        <div class="card-body" style="padding-top:0px !important">
            <div class="users-list-filter">
                <form>
                    <div class="row">

                        <?php
                        $form = ActiveForm::begin([
                                    'action' => ['customer/index', 'storeUuid' => $restaurant_uuid],
                                    'method' => 'get',
                        ]);
                        ?>

                        <div class="col-12 col-md-4">
                            <?= $form->field($model, 'customer_name') ?>
                        </div>


                        <div class="col-12 col-md-4">
                            <?= $form->field($model, 'customer_phone_number') ?>
                        </div>
                        <div class="col-12 col-md-4">
                          <?=
                             $form->field($model, 'date_range', [
                             ])->widget(DateRangePicker::classname(), [
                                 'presetDropdown' => false,
                                 'convertFormat' => true,
                                 'pluginOptions' => ['locale' => ['format' => 'Y-m-d H:m:s']]
                             ])->label('Created At');
                         ?>
                        </div>

                      </div>


                        <div class="form-group">
                            <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
                            <?= Html::a('Reset', ['customer/index', 'storeUuid' => $restaurant_uuid], ['class' => 'btn btn-outline-secondary', 'style' => 'margin-left: 10px;']) ?>
                        </div>
                        <?php ActiveForm::end(); ?>

                </form>
            </div>
        </div>

    </div>
</div>
