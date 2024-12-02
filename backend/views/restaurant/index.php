<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\RestaurantSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $downloadUrl string */
/* @var $totalFilter number */

$this->title = 'Restaurants';
$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css">
    .btn {
        margin-inline-start: 7px;
    }
</style>
<div class="restaurant-index">

    <h1>
        <?= Html::encode($this->title) ?>

        <?php

        $selected = $totalFilter > 0 ? '&nbsp;<b>('.$totalFilter . " selected)</b>": "";

        echo Html::a('Search with more options' .$selected, ['filter'], ['class' => 'pull-right btn btn-primary']);

        echo Html::a('Download', urldecode($downloadUrl), ['class' => 'pull-right btn btn-warning']); ?>


    </h1>

    <p>
        <?php 
        // echo Html::a('Create Restaurant', ['create'], ['class' => 'btn btn-success']);
         ?>
    </p>

    <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'rowOptions'=>function($model){
                        if (!str_contains($model->restaurant_domain, ".site") && $model->queue) {
                            if ($model->queue->queue_status == \common\models\Queue::QUEUE_STATUS_PENDING) {
                                return ['class' => 'danger'];
                            } else if ($model->queue->queue_status == \common\models\Queue::QUEUE_STATUS_HOLD) {
                                return ['style' => 'background:orange', 'title' => 'Hold'];
                            }
                        }
                    },
                    'columns' => [
                        // ['class' => 'yii\grid\SerialColumn'],
                        /*[
                            'attribute' => 'logo',
                            'format' => 'html',
                            'value' => function ($data) {
                                return Html::img($data->getRestaurantLogoUrl());
                            },
                        ],*/
                        [
                            'attribute' => 'name',
                            'label' => "Name",
                            'format' => 'raw',
                            'value' => function ($data) {

                                if (!str_contains($data->restaurant_domain, ".site") && $data->queue) {

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
                                //$data->queue->queue_status .
                                    $name = $data->name . ' ' . '&nbsp;&nbsp;' . $icon;
                                } else {
                                    $name = $data->name;
                                }

                                if($data->is_deleted)
                                    $name .= '&nbsp;&nbsp;<span class="badge badge-danger">Deleted</span>';

                                return $name;
                            }
                        ],
                       /* [

                            'label' => "Category",
                            //'filter' => \common\models\BusinessCategory::arrFilter(),
                            'value' => function ($data) {
                                if($data->businessCategory)
                                    return $data->businessCategory->business_category_en;
                            }
                        ],*/
                        [
                            'attribute' => 'restaurant_domain',
                            'label' => "URL",
                            'format' => 'raw',
                            'value' => function ($data) {
                                return '<a target="_blank" href="'. $data->restaurant_domain .'">'. $data->restaurant_domain .'</a>';
                            }
                        ],

                     //   'ip_address',

                        //'country_name',
                        'total_orders',
                        /*[
                          'attribute' => 'country_name',
                          'value' =>     'country.country_name'
                        ],
                        [
                          'label' => 'Currency',
                          'attribute' => 'currency_title',
                          'value' =>     'currency.title'
                        ],*/
                        //'platform_fee:percent',
                       // 'warehouse_fee',
                       // 'warehouse_delivery_charges',
                        // 'version',
                        //'restaurant_created_at:datetime',
                        //'referral_code',
                        //'last_active_at',
                        [
                               "attribute" => "is_tap_enable",
                            "filter" => [
                                1 => 'Yes',
                                0 => 'No',
                            ],
                            "value" => function ($data) {
                                return $data->is_tap_enable ? "Yes": "No";
                            }
                        ],
                        [
                               "attribute"=> "tap_merchant_status",
                            'filter' => [
                                "Unknown" => "Unknown",
                                "Active" => 'Active',
                                "New Pending Approval" => 'New Pending Approval',
                                "Closed" => "Closed"
                            ],
                        ],
                        'last_order_at',
                        [
                            'attribute' => 'restaurant_status',
                            'filter' => \common\models\Restaurant::arrStatus(),
                            'value' =>   'status',
                        ],
                        'restaurant_created_at:date',
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
