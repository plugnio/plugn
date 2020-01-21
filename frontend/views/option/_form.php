<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Option */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="option-form">

    <?php $form = ActiveForm::begin(); ?>



    <?= $form->field($model, 'option_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'option_name_ar')->textInput(['maxlength' => true]) ?>

    <?=
    $form->field($model, 'is_required')->dropDownList(
            [
        1 => 'Yes',
        0 => 'No',
            ]
            , ['prompt' => 'Choose...']
    );
    ?>

    <?= $form->field($model, 'max_qty')->textInput() ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
