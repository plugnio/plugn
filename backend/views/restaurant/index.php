<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\RestaurantSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Restaurants';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="restaurant-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php

        // echo Html::a('Create Restaurant', ['create'], ['class' => 'btn btn-success']);
         ?>
    </p>

    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions'=>function($model){
            if ($model->queue) {
                if ($model->queue->queue_status == \common\models\Queue::QUEUE_STATUS_PENDING) {
                    return ['class' => 'danger'];
                } else if ($model->queue->queue_status == \common\models\Queue::QUEUE_STATUS_HOLD) {
                    return ['style' => 'background:orange', 'title' => 'Hold'];
                }
            }
        },
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'logo',
                'format' => 'html',
                'value' => function ($data) {
                    return Html::img($data->getRestaurantLogoUrl());
                },
            ],
            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function ($data) {
                    if ($data->queue) {

                        $icon = "";

                        if ($data->queue->queue_status == 1) {
                            $icon = Html::a('<i class="glyphicon glyphicon-minus-sign" style="color:red"></i>', ['queue/view', 'id' => $data->queue->queue_id], ['title' => 'Pending']);
                        } else if ($data->queue->queue_status == 2) {
                            $icon = Html::a('<i class="glyphicon glyphicon-exclamation-sign" style="color:orange"></i>', ['queue/view', 'id' => $data->queue->queue_id], ['title' => 'Creating']);
                        } else if ($data->queue->queue_status == 3) {
                            $icon = Html::a('<i class="glyphicon glyphicon-ok-sign" style="color:green"></i>', ['queue/view', 'id' => $data->queue->queue_id], ['title' => 'Published']);
                        } else if ($data->queue->queue_status == 4) {
                            $icon = Html::a('<i class="glyphicon glyphicon glyphicon-time" style="color:black"></i>', ['queue/view', 'id' => $data->queue->queue_id], ['title' => 'Hold']);
                        } else if ($data->queue->queue_status == 5) {
                            $icon = Html::a('<i class="glyphicon glyphicon-exclamation-sign"></i> Failed', ['queue/view', 'id' => $data->queue->queue_id], ['title' => 'Failed']);

                        }

                        return $data->name . ' ' . $data->queue->queue_status . '&nbsp;&nbsp;' . $icon;
                    } else {
                        return $data->name;
                    }
                }
            ],
            'restaurant_domain',
            /*[
              'attribute' => 'country_name',
              'value' =>     'country.country_name'
            ],
            [
              'label' => 'Currency',
              'attribute' => 'currency_title',
              'value' =>     'currency.title'
            ],*/
            'platform_fee:percent',
            //'warehouse_fee',
            //'warehouse_delivery_charges',
           // 'version',
           // 'restaurant_created_at:datetime',
            //'referral_code',
            'last_active_at',
            'last_order_at',
            'status',
            //'is_deleted',
            [
                'class' => 'yii\grid\ActionColumn',
                'controller' => 'restaurant',
                //'template' => ' {view} {update}'
            ],


            // ['class' => 'yii\grid\ActionColumn','template' => '{view}{update}'],
        ],
    ]); ?>


</div>
