<?php

use common\models\StoreDomainSubscription;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\StoreDomainSubscriptionSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Store Domain Subscriptions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="store-domain-subscription-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Store Domain Subscription'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'subscription_uuid',
            //'restaurant_uuid',
            "restaurantName",
            //'domain_registrar',
            'domain:url',
            'from:date',
            'to:date',
            //'created_by',
            //'updated_by',
            'created_at:datetime',
            'updated_at:datetime',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, StoreDomainSubscription $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'subscription_uuid' => $model->subscription_uuid]);
                 }
            ],
        ],
    ]); ?>


</div>
