<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Item;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\OrderItem */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="order-item-form">

    <?php
    $form = ActiveForm::begin([
      'errorSummaryCssClass' => 'alert alert-danger'
    ]);
    ?>

    <?= $form->errorSummary($model,['header' => '<h4 class="alert-heading">Please fix the following errors:</h4>']); ?>

    <?php
    if (isset($restaurantsItems)) {
        echo $form->field($model, 'item_uuid')->dropDownList($restaurantsItems, ['class' => 'form-control select2'])->label('Item');
    }
    ?>


    <?= $form->field($model, 'qty')->textInput() ?>

    <?= $form->field($model, 'customer_instruction')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
    <?= Html::submitButton('Save', ['class' => 'btn btn-success' ,'style' => 'width: 100%; height: 50px;']) ?>
    </div>

<?php ActiveForm::end(); ?>

</div>
