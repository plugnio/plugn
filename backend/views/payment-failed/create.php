<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PaymentFailed */

$this->title = Yii::t('app', 'Create Payment Failed');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Payment Faileds'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-failed-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
