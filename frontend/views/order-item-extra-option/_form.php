<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="order-item-extra-options-form">

    <?php
    $form = ActiveForm::begin();
    ?>

    <?= $form->errorSummary($model); ?>

    <?php
    if (isset($extraOptionsQuery)) {
        echo $form->field($model, 'extra_option_id')->dropDownList($extraOptionsQuery, ['class' => 'select2'])->label('Extra Option');
    }
    ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success','style' => 'width: 100%; height: 50px;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
