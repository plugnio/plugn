<?php

use yii\helpers\Html;


$this->title = 'Delivery areas for ' . $city->city_name . ', ' . $model->businessLocation->country->country_name;

$this->params['breadcrumbs'][] = ['label' => 'Business Locations', 'url' => ['business-location/index', 'storeUuid' => $model->businessLocation->restaurant_uuid]];

$this->params['breadcrumbs'][] = [
    'label' => 'Delivery Zones', 'url' => [
        'delivery-zone/index',
        'businessLocationId' => $model->businessLocation->business_location_id,
        'storeUuid' => $model->businessLocation->restaurant_uuid
    ]
];

$this->params['breadcrumbs'][] = [
    'label' => 'Cities', 'url' => [
        'delivery-zone/cities',
        'id' => $model->delivery_zone_id,
        'storeUuid' => $model->businessLocation->restaurant_uuid
    ]
];

$this->params['breadcrumbs'][] = $this->title;

$this->params['restaurant_uuid'] = $model->restaurant_uuid;

$selectedAreas = $model->getAreaDeliveryZones()
    ->all();

$selectedAreas = \yii\helpers\ArrayHelper::map($selectedAreas, 'area_id', 'area_id');

?>

<?= \yii\helpers\Html::a(
    'Enable All',
    [
        'enable-all', 'storeUuid' => $model->restaurant_uuid, 'city_id' => $city->city_id, 'id' => $model->delivery_zone_id],
    [
        'class' => 'btn btn-primary'
    ]
); ?>

<?= \yii\helpers\Html::a(
    'Disable All',
    ['disable-all', 'storeUuid' => $model->restaurant_uuid, 'city_id' => $city->city_id, 'id' => $model->delivery_zone_id],
    [
        'class' => 'btn btn-danger'
    ]
); ?>

    <br /><br />

<?= \yii\grid\GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        'area_name',
        'area_name_ar',
        [
            'label' => 'Covering?',
            //'format' => 'raw',
            'value' => function ($area) use ($selectedAreas) {

                $checked = in_array($area->area_id, $selectedAreas) ? true: false;

                return $checked? 'Yes': 'No';
                //return '<input type="checkbox" name="area['.$area->area_id.']" checked="'.$checked.'" />';
            }
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{enable} {disable}',
            'buttons' => [
                'enable' => function ($url, $data) use ($model, $selectedAreas) {

                    //if disabled, show enable button

                    if(in_array($data->area_id, $selectedAreas))
                        return null;

                    return \yii\helpers\Html::a(
                        'Enable',
                        ['enable', 'storeUuid' => $model->restaurant_uuid, 'area_id' => $data->area_id, 'id' => $model->delivery_zone_id],
                        [
                            'title' => 'Enable',
                            'data-pjax' => '0',
                        ]
                    );
                },
                'disable' => function ($url, $data) use ($model, $selectedAreas) {

                    if(!in_array($data->area_id, $selectedAreas))
                        return null;

                    return \yii\helpers\Html::a(
                        'Disable',
                        ['disable', 'storeUuid' => $model->restaurant_uuid, 'area_id' => $data->area_id, 'id' => $model->delivery_zone_id],
                        [
                            'title' => 'Disable',
                            'data-pjax' => '0',
                        ]
                    );
                },
            ],
        ],
    ],
]);

