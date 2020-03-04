<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\RestaurantDeliverySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'By City';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="card">


    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'city.city_name',
            'min_delivery_time',
            'delivery_fee',
          
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => ' {view} {update} {delete}',
                'buttons' => [
                  
                    'update' => function ($url ,$model) {
                        $url = Url::to(['restaurant-delivery/update-delivery-time-for-city', 'area_id' => $model->area_id, 'city_id' => $model->area->city_id]);
                        return Html::a(
                                        '<span style="margin-right: 20px;" class="nav-icon fas fa-edit"></span>', $url, [
                                    'title' => 'Update',
                                    'data-pjax' => '0',
                                        ]
                        );
                    },
                    'delete' => function ($url) {
                        return Html::a(
                                        '<span style="margin-right: 20px;" class="nav-icon fas fa-trash"></span>', $url, [
                                    'title' => 'Delete',
                                    'data' => [
                                        'confirm' => 'Are you absolutely sure ? You will lose all the information about this category with this action.',
                                        'method' => 'post',
                                    ],
                        ]);
                    },
                ],
            ],
        ],
        'layout' => '{summary}<div class="card-body">{items}{pager}</div>',
        'tableOptions' => ['class' => 'table table-bordered table-hover'],
        'summaryOptions' => ['class' => "card-header"],
    ]);
    ?>


</div>


