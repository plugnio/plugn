<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Refund;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\RefundSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Refunds';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="refund-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Refund', ['create'], ['class' => 'btn btn-success btn-create']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
//            'refund_id',
            'payment_uuid',
            [
              'attribute' => 'store_name',
              'value' =>     'store.name'
            ],
            'order_uuid',
            [
                'attribute' => 'refund_status',
                'filter' => ['Initiated'=>'Initiated','CANCELLED'=>'CANCELLED','PENDING'=>'PENDING','REFUNDED'=>'REFUNDED'],
                'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'All']
            ],
            [
                'label' => 'Payment Status',
                'value' => function($data) {
                    return ($data->payment) ? $data->payment->payment_current_status : '-';
                }
            ],
            [
                'label' => 'Payment Gateway',
                'format' => 'html',
                'value' => function($data) {
                    if ($data->payment) {
                        $str = $data->payment->payment_gateway_name;
                        $str .= ($data->store->is_myfatoorah_enable) ? '(myfatoorah)' : '(tap_enable)';
                        return $str;
                    } else {
                        return '-';
                    }
                }
            ],
            'refund_amount',
            [
                'attribute' => 'refund_amount',
                'value' => function ($data) {
                    return Yii::$app->formatter->asCurrency($data->refund_amount, $data->currency->code, [
                        \NumberFormatter::MIN_FRACTION_DIGITS => $data->currency->decimal_place,
                        \NumberFormatter::MAX_FRACTION_DIGITS => $data->currency->decimal_place
                    ]);
                }
            ],
            [
                'attribute' => 'refund_created_at',
                'value' => function ($data) {
                    return Yii::$app->formatter->asDate($data->refund_created_at);
                },
                'filter'=>false
            ],
            //'reason',
            //'refund_created_at',
            //'refund_updated_at',
            'refund_reference',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
