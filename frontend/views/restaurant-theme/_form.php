<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\RestaurantTheme */
/* @var $form yii\widgets\ActiveForm */

$js = " 
    //color picker with addon
    $('.primary-colorpicker').colorpicker()
    $('.secondary-colorpicker').colorpicker()
    $('.tertiary-colorpicker').colorpicker()
    $('.medium--colorpicker').colorpicker()
    $('.dark--colorpicker').colorpicker()

    $('.primary-colorpicker').on('colorpickerChange', function(event) {
      $('.primary-colorpicker .fa-square').css('color', event.color.toString());
    });
    
    $('.secondary-colorpicker').colorpicker()

    $('.secondary-colorpicker').on('colorpickerChange', function(event) {
      $('.secondary-colorpicker .fa-square').css('color', event.color.toString());
    });
    
    $('.tertiary-colorpicker').colorpicker()

    $('.tertiary-colorpicker').on('colorpickerChange', function(event) {
      $('.tertiary-colorpicker .fa-square').css('color', event.color.toString());
    });
    
    $('.medium-colorpicker').colorpicker()

    $('.medium-colorpicker').on('colorpickerChange', function(event) {
      $('.medium-colorpicker .fa-square').css('color', event.color.toString());
    });
    
    $('.dark-colorpicker').colorpicker()

    $('.dark-colorpicker').on('colorpickerChange', function(event) {
      $('.dark-colorpicker .fa-square').css('color', event.color.toString());
    });
    

    ";

$this->registerJs($js);
?>

<div class="restaurant-theme-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <?=
    $form->field($model, 'primary', [
        'template' => "{label}"
        . "            <div class='form-group'>"
        . "             <div class='input-group primary-colorpicker'>"
        . "                 {input}"
        . "                <div class='input-group-append'>"
        . "                 <span class='input-group-text'><i class='fas fa-square'></i></span>"
        . "                </div>"
        . "             </div>"
        . "            </div>"
    ])->textInput(['text' => 'text'])
    ?>
    
    <?=
    $form->field($model, 'secondary', [
        'template' => "{label}"
        . "            <div class='form-group'>"
        . "             <div class='input-group secondary-colorpicker'>"
        . "                 {input}"
        . "                <div class='input-group-append'>"
        . "                 <span class='input-group-text'><i class='fas fa-square'></i></span>"
        . "                </div>"
        . "             </div>"
        . "            </div>"
    ])->textInput(['text' => 'text'])
    ?>
      
    <?=
    $form->field($model, 'tertiary', [
        'template' => "{label}"
        . "            <div class='form-group'>"
        . "             <div class='input-group tertiary-colorpicker'>"
        . "                 {input}"
        . "                <div class='input-group-append'>"
        . "                 <span class='input-group-text'><i class='fas fa-square'></i></span>"
        . "                </div>"
        . "             </div>"
        . "            </div>"
    ])->textInput(['text' => 'text'])
    ?>
      
    <?=
    $form->field($model, 'medium', [
        'template' => "{label}"
        . "            <div class='form-group'>"
        . "             <div class='input-group medium-colorpicker'>"
        . "                 {input}"
        . "                <div class='input-group-append'>"
        . "                 <span class='input-group-text'><i class='fas fa-square'></i></span>"
        . "                </div>"
        . "             </div>"
        . "            </div>"
    ])->textInput(['text' => 'text'])
    ?>
      
      
    <?=
    $form->field($model, 'dark', [
        'template' => "{label}"
        . "            <div class='form-group'>"
        . "             <div class='input-group dark-colorpicker'>"
        . "                 {input}"
        . "                <div class='input-group-append'>"
        . "                 <span class='input-group-text'><i class='fas fa-square'></i></span>"
        . "                </div>"
        . "             </div>"
        . "            </div>"
    ])->textInput(['text' => 'text'])
    ?>
      




    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
