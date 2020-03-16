<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\WorkingDay;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\WorkingHours */
/* @var $form yii\widgets\ActiveForm */

$js = "

  $(function () {

    //Timepicker for operating_from field
    $('#operatingFrom').datetimepicker({
        format: 'H:mm'
    })
    $('#operatingTo').datetimepicker({
      format: 'H:mm'
    })
    

  })
";

$this->registerJs($js);
?>

<div class="working-hours-form">

    <?php
    $workingDaysQuery = common\models\WorkingDay::find()->asArray()->all();
    $workingDayArray = ArrayHelper::map($workingDaysQuery, 'working_day_id', 'name');

    $form = ActiveForm::begin();
    ?>


    <?= $form->field($model, 'working_day_id')->dropDownList($workingDayArray) ?>

    <?=
        $form->field($model, 'operating_from', [
            'template' => "{label}"
            . "            <div class='input-group date' id='operatingFrom' data-target-input='nearest'>"
            . "                 {input}"
            . "              <div class='input-group-append' data-target='#operatingFrom' data-toggle='datetimepicker'>"
            . "                <div class='input-group-text'><i class='far fa-clock'></i></div>"
            . "               </div>"
            . "            </div>"
        ])->textInput(['class' => 'form-control datetimepicker-input'])
    ?>
    
    
    <?=
        $form->field($model, 'operating_to', [
            'template' => "{label}"
            . "            <div class='input-group date' id='operatingTo' data-target-input='nearest'>"
            . "                 {input}"
            . "              <div class='input-group-append' data-target='#operatingTo' data-toggle='datetimepicker'>"
            . "                <div class='input-group-text'><i class='far fa-clock'></i></div>"
            . "               </div>"
            . "            </div>"
        ])->textInput(['class' => 'form-control datetimepicker-input'])
    ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
