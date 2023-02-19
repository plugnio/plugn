<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RestaurantInvoice */

$this->title = Yii::t('app', 'Create Restaurant Invoice');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Restaurant Invoices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="restaurant-invoice-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
