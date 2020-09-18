<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\RestaurantTheme;
use common\models\Restaurant;

/* @var $this yii\web\View */
/* @var $model common\models\Restaurant */

$this->params['restaurant_uuid'] = $model->restaurant_uuid;

$this->title = 'Analytics delivery';
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="restaurant-view">

    <p>
      <?php
      // Html::a('Update', ['update-design-and-layout', 'id' => $model->restaurant_uuid], ['class' => 'btn btn-primary'])
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
                          'attribute' => 'armada_api_key',
                          'format' => 'html',
                          'value' => function ($data) {
                              return $data->armada_api_key;
                          },
                          'visible' => $model->armada_api_key != null,
                      ],
                    ],
                    'options' => ['class' => 'table table-hover text-nowrap table-bordered'],
                ])
                ?>

            </div>
        </div>
    </div>

</div>
