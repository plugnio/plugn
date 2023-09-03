<?php

use backend\models\Admin;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Countries';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="staff-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'country_name',
            [
                'label' => "No of Stores",
                'format' => "html",
                'value' => function ($model) {
                    return $model->getRestaurants()->count();
                }
            ],
            [
                'label' => "Revenue",
                'format' => "html",
                'value' => function ($model) {

                    $payments = $model->getCSV();

                    if(empty($payments)) {
                        return "No order(s)!";
                    }

                    $values = [];

                    foreach ($payments as $payment) {

                        $values[] = Yii::$app->formatter->asCurrency($payment['payment_net_amount'], $payment['currency_code']);
                    }

                    return implode(", ", $values);
                }
            ],
            [
                'label' => "Our Commission",
                'format' => "html",
                'value' => function ($model) {

                    $payments = $model->getCSV();

                    if(empty($payments)) {
                        return "No order(s)!";
                    }

                    $values = [];

                    foreach ($payments as $payment) {

                        $values[] = Yii::$app->formatter->asCurrency($payment['plugn_fees'], $payment['currency_code']);
                    }

                    return implode(", ", $values);
                }
            ],
            [
                'label' => "Payment gateway Charge",
                'format' => "html",
                'value' => function ($model) {

                    $payments = $model->getCSV();

                    if(empty($payments)) {
                        return "No order(s)!";
                    }

                    $values = [];

                    foreach ($payments as $payment) {

                        $values[] = Yii::$app->formatter->asCurrency($payment['payment_gateway_fees'], $payment['currency_code']);
                    }

                    return implode(", ", $values);
                }
            ],
            [
                'label' => "Cash",
                'format' => "html",
                'value' => function ($model) {
                    $payments =  $model->getCashOrderTotal();

                    if(empty($payments)) {
                        return "No order(s)!";
                    }

                    $values = [];

                    foreach ($payments as $payment) {

                        $values[] = Yii::$app->formatter->asCurrency($payment['total'], $payment['currency_code']);
                    }

                    return implode(", ", $values);
                }
            ],

        ],
    ]); ?>
</div>
