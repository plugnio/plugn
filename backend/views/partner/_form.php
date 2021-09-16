<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Partner */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="partner-form">

  <?php

  $form = ActiveForm::begin();


          $bankQuery = common\models\Bank::find()->asArray()->all();
          $bankArray = ArrayHelper::map($bankQuery, 'bank_id', 'bank_name');

  ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'partner_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bank_id')->dropDownList($bankArray, ['prompt' => 'Choose your bank...'])->label('Bank name'); ?>

    <?= $form->field($model, 'benef_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'partner_iban')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'commission')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
