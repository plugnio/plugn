<?php

use common\models\StoreDomainSubscriptionPayment;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\StoreDomainSubscriptionPaymentSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Store Domain Subscription Payments');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="store-domain-subscription-payment-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Store Domain Subscription Payment'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'store_domain_subscription_payment_uuid',
            'subscription_uuid',
            'from',
            'to',
            'total_amount',
            'cost_amount',
            //'created_by',
            //'updated_by',
            //'created_at',
            //'updated_at',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, StoreDomainSubscriptionPayment $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->store_domain_subscription_payment_uuid]);
                 }
            ],
        ],
    ]); ?>


</div>
