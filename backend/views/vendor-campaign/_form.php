<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$templates = \common\models\VendorEmailTemplate::find()->all();

$arrTemplate = \yii\helpers\ArrayHelper::map($templates, 'template_uuid', 'subject');

/* @var $this yii\web\View */
/* @var $model common\models\VendorCampaign */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vendor-campaign-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'template_uuid')->dropDownList($arrTemplate) ?>

    <!-- <?= $form->field($model, 'progress')->textInput() ?>-->

    <?= $form->field($model, 'status')->dropDownList([
            0 => 'Draft',
            1 => "In Process",
            2 => "Completed",
            3 => "In Queue"
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
