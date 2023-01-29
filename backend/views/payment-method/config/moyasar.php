<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $code string */
/* @var $model common\models\User */
/* @var $balanceTransaction common\models\BalanceTransaction */

$this->title = $code;
$this->params['breadcrumbs'][] = ['label' => 'Extensions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $code, 'url' => ['edit', 'code' => $code]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="user-form">

        <?php $form = ActiveForm::begin([]) ?>

        <?= $form->field($model, 'payment_moyasar_api_secret_key')->textInput() ?>

        <?= $form->field($model, 'payment_moyasar_api_key')->textInput() ?>

        <!--
        <?= $form->field($model, 'payment_moyasar_payment_type')->dropDownList([1=>'Debit' , 2 => 'Credit']) ?>

        <?= $form->field($model, 'payment_moyasar_network_type')->dropDownList([1=>'Debit' , 2 => 'Credit']) ?>
-->

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

