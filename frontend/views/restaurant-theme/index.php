<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\RestaurantTheme */

$this->title = 'Store Theme';
$this->params['restaurant_uuid'] = $model->restaurant_uuid;

$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$js = "
    $('#primaryColorInput').change(function(e){
      primaryColor = e.target.value;
      $('#primary-wrapper').css('background-color',primaryColor);
    });


    $('#secondaryColorInput').change(function(e){
      secondaryColor = e.target.value;
      $('#secondary-wrapper').css('background-color',secondaryColor);
    });

    $('#tertiaryColorInput').change(function(e){
      tertiaryColor = e.target.value;
      $('#tertiary-wrapper').css('background-color',tertiaryColor);
    });


    $('#mediumColorInput').change(function(e){
      mediumColor = e.target.value;
      $('#medium-wrapper').css('background-color',mediumColor);
    });

    $('#darkColorInput').change(function(e){
      darkColor = e.target.value;
      $('#dark-wrapper').css('background-color',darkColor);
    });




 ";

$this->registerJs($js);
?>


<div class="restaurant-theme-view">


    <div class="card">

        <?php $form = ActiveForm::begin(); ?>



        <div class="card-body">
            <table id="w0" class="table table-hover text-nowrap table-bordered">
                <tbody>
                    <tr>
                        <th>Primary</th>
                        <td style="padding:0px">
                            <div id="primary-wrapper" style=" cursor: pinter; position: relative;float:right;background:<?= $model->primary ?>" class="text-center colors-container rounded text-white width-100 height-100 d-flex align-items-center justify-content-center mr-1 ml-50 my-1 shadow">

                                <?=
                                $form->field($model, 'primary')->textInput(
                                        [
                                            'type' => 'color',
                                            'style' => 'position: absolute; width: 100%; height: 100%; opacity: 0; cursor: pointer;  top: 0;   right: 0;',
                                            'id' => 'primaryColorInput'
                                ])->label('');
                                ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Secondary</th>
                        <td style="padding:0px">
                            <div id="secondary-wrapper" style="position: relative;float:right;background:<?= $model->secondary ?>" class="text-center colors-container rounded text-white width-100 height-100 d-flex align-items-center justify-content-center mr-1 ml-50 my-1 shadow">

                                <?=
                                $form->field($model, 'secondary')->textInput(
                                        [
                                            'type' => 'color',
                                            'style' => 'position: absolute; width: 100%; height: 100%; opacity: 0; cursor: pointer;  top: 0;   right: 0;',
                                            'id' => 'secondaryColorInput'
                                ])->label('');
                                ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Tertiary</th>
                        <td style="padding:0px">
                            <div id="tertiary-wrapper" style="position: relative;float:right;background:<?= $model->tertiary ?>" class="text-center colors-container rounded text-white width-100 height-100 d-flex align-items-center justify-content-center mr-1 ml-50 my-1 shadow">

                                <?=
                                $form->field($model, 'tertiary')->textInput(
                                        [
                                            'type' => 'color',
                                            'style' => 'position: absolute; width: 100%; height: 100%; opacity: 0; cursor: pointer;  top: 0;   right: 0;',
                                            'id' => 'tertiaryColorInput'
                                ])->label('');
                                ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Medium</th>
                        <td style="padding:0px">
                            <div id="medium-wrapper" style="position: relative;float:right;background:<?= $model->medium ?>" class="text-center colors-container rounded text-white width-100 height-100 d-flex align-items-center justify-content-center mr-1 ml-50 my-1 shadow">

                                <?=
                                $form->field($model, 'medium')->textInput(
                                        [
                                            'type' => 'color',
                                            'style' => 'position: absolute; width: 100%; height: 100%; opacity: 0; cursor: pointer;  top: 0;   right: 0;',
                                            'id' => 'mediumColorInput'
                                ])->label('');
                                ?>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <th>Dark</th>
                        <td style="padding:0px">
                            <div id="dark-wrapper" style="position: relative;float:right;background:<?= $model->dark ?>" class="text-center colors-container rounded text-white width-100 height-100 d-flex align-items-center justify-content-center mr-1 ml-50 my-1 shadow">

                                <?=
                                $form->field($model, 'dark')->textInput(
                                        [
                                            'type' => 'color',
                                            'style' => 'position: absolute; width: 100%; height: 100%; opacity: 0; cursor: pointer;  top: 0;   right: 0;',
                                            'id' => 'darkColorInput'
                                ])->label('');
                                ?>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>

                    <div>
                        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width: 100%;height: 50px;']) ?>
                    </div>

        </div>


        <?php ActiveForm::end(); ?>

    </div>


</div>
