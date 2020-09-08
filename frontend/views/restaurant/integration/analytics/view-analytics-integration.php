<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\RestaurantTheme;
use common\models\Restaurant;

/* @var $this yii\web\View */
/* @var $model common\models\Restaurant */

$this->params['restaurant_uuid'] = $model->restaurant_uuid;

$this->title = 'Analytics integration';
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="restaurant-view">

    <p>
      <?php
      // Html::a('Update', ['update-analytics-integration', 'id' => $model->restaurant_uuid], ['class' => 'btn btn-primary'])
      ?>

    </p>
    <div class="card">
        <div class="card-body">
            <div class="box-body table-responsive no-padding">

                <?=
                DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                      [
                          'attribute' => 'google_analytics_id',
                          'format' => 'html',
                          'value' => function ($data) {
                              return $data->google_analytics_id;
                          },
                          'visible' => $model->google_analytics_id != null,
                      ],
                      [
                          'attribute' => 'facebook_pixil_id',
                          'format' => 'html',
                          'value' => function ($data) {
                              return $data->facebook_pixil_id;
                          },
                          'visible' => $model->facebook_pixil_id != null,
                      ],
                    ],
                    'options' => ['class' => 'table table-hover text-nowrap table-bordered'],
                ])
                ?>

            </div>
        </div>
    </div>

</div>
