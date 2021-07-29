<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel partners\models\PartnerPayoutSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Payouts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="partner-payout-index">


    <h3><?= 'Pending ' ?></h3>

    <h1><?=  Yii::$app->formatter->asCurrency($partner->getTotalEarnings(), 'KWD',[ \NumberFormatter::MIN_FRACTION_DIGITS => 4, \NumberFormatter::MAX_FRACTION_DIGITS => 4 ]) ?></h1>
    <br/>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'label' => 'Date',
                "value" => function($data) {
                        return Yii::$app->formatter->asDate($data->updated_at,  'php:M d, Y');
                },
            ],
            [
                'label' => 'Amount',
                'format' => 'raw',
                'value' => function ($data) {
                    return Yii::$app->formatter->asCurrency($data->amount, 'KWD',[ \NumberFormatter::MIN_FRACTION_DIGITS => 4, \NumberFormatter::MAX_FRACTION_DIGITS => 4 ]);
                }
            ],
        ],
    ]); ?>


</div>
