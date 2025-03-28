<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\StoreDomainSubscriptionSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="store-domain-subscription-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'subscription_uuid') ?>

    <?= $form->field($model, 'restaurant_uuid') ?>

    <?= $form->field($model, 'restaurantName') ?>

    <?= $form->field($model, 'domain_registrar') ?>

    <?= $form->field($model, 'domain') ?>

    <?= $form->field($model, 'from')->textInput(['type' => "date"]) ?>

    <?php echo $form->field($model, 'to')->textInput(['type' => "date"]) ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
