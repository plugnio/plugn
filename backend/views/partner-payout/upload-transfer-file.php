<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PartnerPayout */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="partner-payout-form">

    <?php $form = ActiveForm::begin(); ?>

    <?=
    $form->field($model, 'excel')->fileInput();
    ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
