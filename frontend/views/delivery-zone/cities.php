<?php

$this->title = 'Cities for ' . $model->businessLocation->business_location_name . ' | ' . $model->businessLocation->country->country_name;

$this->params['breadcrumbs'][] = ['label' => 'Business Locations', 'url' => ['business-location/index', 'storeUuid' => $model->businessLocation->restaurant_uuid]];

$this->params['breadcrumbs'][] = [
    'label' => 'Delivery Zones', 'url' => [
        'delivery-zone/index',
        'businessLocationId' => $model->businessLocation->business_location_id,
        'storeUuid' => $model->businessLocation->restaurant_uuid
    ]
];

$this->params['breadcrumbs'][] = $this->title;

$this->params['restaurant_uuid'] = $model->restaurant_uuid;

echo \yii\grid\GridView::widget([
    'dataProvider' => $cityProvider,
    'filterModel' => $searchModel,
    'columns' => [
        //'city_id',
        'city_name',
        'city_name_ar',
        [
            'label' => 'Covering',
            'value' => function ($city) use ($model) {

                $covered = \agent\models\AreaDeliveryZone::find()->where([
                    'city_id' => $city->city_id,
                    'delivery_zone_id' => $model->delivery_zone_id
                ])->count();

                $total = $city->getAreas()->count();

                return $covered . ' Out of ' . $total . ' area(s)';
            }
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{update-areas}',
            'buttons' => [
                'update-areas' => function ($url, $data) use ($model) {

                    return \yii\helpers\Html::a(
                        'Edit',
                        ['update-areas', 'storeUuid' => $model->restaurant_uuid, 'city_id' => $data->city_id, 'id' => $model->delivery_zone_id],
                        [
                            'title' => 'Edit',
                            'data-pjax' => '0',
                        ]
                    );
                },
            ],
        ],
    ],
]);
