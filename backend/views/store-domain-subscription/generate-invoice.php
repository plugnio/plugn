<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\StoreDomainSubscription $model */
/** @var common\models\StoreDomainSubscriptionPayment $storeDomainSubscriptionPayment */
/** @var yii\widgets\ActiveForm $form */

$this->title = Yii::t('app', 'Generate Invoice');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Store Domain Subscriptions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Subscription for '. $model->restaurant->name), 'url' => ['view/' . $model->subscription_uuid]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="store-domain-subscription-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="store-domain-subscription-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($storeDomainSubscriptionPayment, 'from')->textInput(["type" => "date"]) ?>

        <?= $form->field($storeDomainSubscriptionPayment, 'to')->textInput(["type" => "date"]) ?>

        <?= $form->field($storeDomainSubscriptionPayment, 'total_amount')->textInput(["type" => "number"]) ?>

        <?= $form->field($storeDomainSubscriptionPayment, 'cost_amount')->textInput(["type" => "number"]) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Generate'), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
