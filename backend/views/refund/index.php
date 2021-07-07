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
        <?= Html::a('Create Refund', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'payment_uuid',
            [
              'attribute' => 'store_name',
              'value' =>     'store.name'
            ],
            'order_uuid',
            'refund_status',
            'refund_amount',
            [
                'attribute' => 'refund_amount',
                'value' => function ($data) {
                    return Yii::$app->formatter->asCurrency($data->refund_amount, $data->currency->code);
                }
            ],
            //'reason',
            //'refund_created_at',
            //'refund_updated_at',
            //'refund_reference',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
