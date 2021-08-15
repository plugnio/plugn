<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel partner\models\PartnerPayoutSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Payouts';
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="partner-payout-index">

  <h1 style="margin-bottom:20px"><?= Html::encode($this->title) ?></h1>


      <div  style="    box-shadow: var(--p-card-shadow,0 0 0 1px rgba(63,63,68,.05),0 1px 3px 0 rgba(63,63,68,.15));
        padding: 2rem;">

          <h3 style="margin:0px;display: contents;"><?= 'Pending ' ?></h3>
          <p>
                <?= $totalEarnings ? Html::a('View transactions', ['pending'], ['style' => 'float:right !important']) : '' ?>
          </p>

          <h1><?=  Yii::$app->formatter->asCurrency($totalEarnings ? $totalEarnings : 0, 'KWD',[ \NumberFormatter::MIN_FRACTION_DIGITS => 4, \NumberFormatter::MAX_FRACTION_DIGITS => 4 ]) ?></h1>
        </div>
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
            [
                'label' => 'Status',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->status;
                }
            ],
        ],
    ]); ?>


</div>
