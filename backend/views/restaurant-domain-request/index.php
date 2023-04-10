<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\RestaurantDomainRequest;

/* @var $this yii\web\View */
/* @var $searchModel common\models\RestaurantDomainRequestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Restaurant Domain Requests');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="restaurant-domain-request-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Restaurant Domain Request'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'storeName',
                'value' => 'restaurant.name'
            ],
            //'request_uuid',
            'domain',
            [
                    'attribute' => 'status',
                    'filter' => RestaurantDomainRequest::arrStatus(),
                    'value' => function($data) {
                        return RestaurantDomainRequest::arrStatus()[$data->status];
                    }
            ],
            //'created_by',
            'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
