<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$templates = \common\models\VendorEmailTemplate::find()->all();

$arrTemplate = \yii\helpers\ArrayHelper::map($templates, 'template_uuid', 'subject');

$currencies = \common\models\Currency::find()->all();
$currencies = ["0" => "All"] + $currencies;

$countries = \common\models\Country::find()->all();
$countries = ["0" => "All"] + $countries;

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

    <?php if(!$model->campaign_uuid) { ?>

    <div class="form-group">
        <h5>Filters</h5>
    <!--
    <?= Html::input('text', 'CampaignFilter[warned_delete_at]', '', ['class' => 'form-control']) ?>
    -->

        <div class="form-group">
            <label class="control-label">Total orders</label>
    <?= Html::input('text', 'CampaignFilter[total_orders]', '', ['class' => 'form-control']) ?>
        </div>

    <div class="form-group">
        <label class="control-label">Version</label>
    <?= Html::input('text', 'CampaignFilter[version]', '', [
        'class' => 'form-control']) ?>
    </div>

    <div class="form-group">
        <label class="control-label">Country</label>
    <?= Html::dropDownList('CampaignFilter[country_id]', null, \yii\helpers\ArrayHelper::map($countries, 'country_id', 'country_name'), ['class' => 'form-control']) ?>
    </div>

        <div class="form-group">
            <label class="control-label">Currency</label>
        <?= Html::dropDownList('CampaignFilter[currency_id]', null, \yii\helpers\ArrayHelper::map($currencies, 'currency_id', 'code'), ['class' => 'form-control']) ?>
        </div>

    <?= Html::checkbox('CampaignFilter[enable_debugger]', false, ['label' => 'Debugger enabled']) ?>
    <?= Html::checkbox('CampaignFilter[accept_order_247]', false, ['label' => 'Open 24x7']) ?>
    <?= Html::checkbox('CampaignFilter[is_sandbox]', false, ['label' => 'Sandbox']) ?>
    <?= Html::checkbox('CampaignFilter[is_under_maintenance]', false, ['label' => 'Under maintenance']) ?>
    <!--
    <?= Html::checkbox('CampaignFilter[is_deleted]', false, ['label' => 'Deleted']) ?> -->


    <?= Html::checkbox('CampaignFilter[support_pick_up]', false, ['label' => 'Support store pickup']) ?>
    <?= Html::checkbox('CampaignFilter[support_delivery]', false, ['label' => 'Support order delivery']) ?>
    <?= Html::checkbox('CampaignFilter[is_myfatoorah_enable]', false, ['label' => 'MyFatoorah enabled']) ?>
    <?= Html::checkbox('CampaignFilter[not_for_profit]', false, ['label' => 'Not for profit']) ?>
    <?= Html::checkbox('CampaignFilter[is_tap_enable]', false, ['label' => 'Tap enabled']) ?>
    <?= Html::checkbox('CampaignFilter[is_public]', false, ['label' => 'Public']) ?>
    </div>
    <?php } ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
