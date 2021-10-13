<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PartnerPayoutSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Payable Partners';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="partner-payout-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <p>
        <?= Html::a('Download Transfer File', ['download-transfer-file'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Upload Tranfer File', ['import-excel'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'partner_payout_uuid',
            [
              'attribute' => 'partner_username',
              'value' =>     'partner.username'
            ],
            'amount',
            [
              'attribute' => 'payout_status',
              'value' =>     'status'
            ],
            'transferFile',
            'created_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
