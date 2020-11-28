<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\RestaurantTheme;
use common\models\Restaurant;

/* @var $this yii\web\View */
/* @var $model common\models\Restaurant */

$this->params['restaurant_uuid'] = $model->restaurant_uuid;

$this->title = 'Store design and layout';
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="restaurant-view">

    <p>
      <?= Html::a('Update', ['update-design-layout', 'id' => $model->restaurant_uuid], ['class' => 'btn btn-primary']) ?>

    </p>
    <div class="card">
        <div class="card-body">
            <div class="box-body table-responsive no-padding">

                <?=
                DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        [
                            'attribute' => 'thumbnail_image',
                            'format' => 'html',
                            'value' => function ($data) {
                                return Html::img($data->getRestaurantThumbnailImageUrl());
                            },
                        ],
                        [
                            'attribute' => 'logo',
                            'format' => 'html',
                            'value' => function ($data) {
                                return Html::img($data->getRestaurantLogoUrl());
                            },
                        ],
                        [
                            'attribute' => 'phone_number_display',
                            'format' => 'html',
                            'value' => function ($data) {
                                if ($data->phone_number_display == Restaurant::PHONE_NUMBER_DISPLAY_DONT_SHOW_PHONE_NUMBER)
                                    return "Dont show store's phone number";
                                else if($data->phone_number_display == Restaurant::PHONE_NUMBER_DISPLAY_ICON)
                                         return "📞";
                                else if($data->phone_number_display == Restaurant::PHONE_NUMBER_DISPLAY_SHOW_PHONE_NUMBER)
                                         return "+965" . $data->phone_number;
                            },
                        ],
                        [
                            'attribute' => 'store_layout',
                            'format' => 'html',
                            'value' => function ($data) {
                                return $data->store_layout == 1 ? 'List' : 'Grid';
                            }
                        ],
                        [
                            'attribute' => 'default_language',
                            'format' => 'html',
                            'value' => function ($data) {
                                  return $data->default_language == 'en' ? 'English' : 'عربي';
                            },
                        ],
                        [
                            'label' => 'Theme Color',
                            'format' => 'html',
                            'value' => function ($data) {

                               $store_theme_model = RestaurantTheme::findOne($data->restaurant_uuid);
                                return
                                '<div id="primary-wrapper"
                                style=" cursor: pinter; width:50%; margin-bottom: 21px; position: relative;
                                height: 50px;   margin: 0px !important;
                                background:' . $store_theme_model->primary . '"
                                class="text-center colors-container rounded text-white  height-40 d-flex align-items-center justify-content-center  shadow">';
                            }
                        ],
                    ],
                    'options' => ['class' => 'table table-hover text-nowrap table-bordered'],
                ])
                ?>

            </div>
        </div>
    </div>

</div>
