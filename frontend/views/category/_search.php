<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\CategorySearch */
/* @var $form yii\widgets\ActiveForm */
?>



<!-- users filter start -->
<div class="card">



    <div class="card-header">
        <h4 class="card-title">Filters</h4>
        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
        <div class="heading-elements">
            <ul class="list-inline mb-0">
                <li><a data-action="collapse"><i class="feather icon-chevron-down"></i></a></li>
                <li><a data-action="close"><i class="feather icon-x"></i></a></li>
            </ul>
        </div>
    </div>
    <div class="card-content collapse show">


        <div class="card-body">
            <div class="users-list-filter">
                <form>
                    <div class="row">

                        <?php
                        $form = ActiveForm::begin([
                            'action' => ['category/index', 'storeUuid' => $restaurant_uuid],
                            'method' => 'get',
                        ]);
                        ?>


                        <div class="col-12 col-sm-6 col-lg-3">
                          <?= $form->field($model, 'title') ?>
                        </div>

                        <div class="col-12 col-sm-6 col-lg-3">
                          <?= $form->field($model, 'title_ar') ?>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-3">
                          <?= $form->field($model, 'subtitle') ?>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-3">
                          <?= $form->field($model, 'subtitle_ar') ?>
                        </div>




                        <div class="form-group" style="margin: 0px 15px">
                            <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
                            <?= Html::a('Reset', ['category/index', 'storeUuid' => $restaurant_uuid], ['class' => 'btn btn-outline-secondary', 'style' => 'margin-left: 10px;']) ?>
                        </div>
                        <?php ActiveForm::end(); ?>

                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
