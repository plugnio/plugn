<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Order */

$this->params['restaurant_uuid'] = $model->restaurant_uuid;

$this->title = 'Update Order: #' . $model->order_uuid;
$this->params['breadcrumbs'][] = ['label' => 'Order #' . $model->order_uuid, 'url' => ['order/view', 'id' => $model->order_uuid,'restaurantUuid' => $model->restaurant_uuid]];
$this->params['breadcrumbs'][] = 'Update';


$js = "
$(function () {
  $('.summary').insertAfter('.top');
});
";
$this->registerJs($js);
?>
<div class="order-update">

    <?=
    $this->render('_form', [
        'model' => $model,
        'restaurant_model' => $restaurant_model,
    ])
    ?>





    <section id="data-list-view" class="data-list-view-header">

      <h2>Items</h2>


    <!-- Data list view starts -->
    <div class="action-btns">
        <div class="btn-dropdown mr-1 mb-1">
            <div class="btn-group dropdown actions-dropodown">
              <?= Html::a('<i class="feather icon-plus"></i> Add New', ['order-item/create', 'id' => $model->order_uuid, 'restaurantUuid' => $model->restaurant->restaurant_uuid], ['class' => 'btn btn-outline-primary']) ?>
            </div>
        </div>
    </div>


        <!-- DataTable starts -->
        <div class="table-responsive">

            <?=
            GridView::widget([
                'dataProvider' => $ordersItemDataProvider,
                'rowOptions' => function($model) {
                    $url = Url::to(['order-item/view', 'id' => $model->order_item_id, 'restaurantUuid' => $model->order->restaurant_uuid]);

                    return [
                        'onclick' => "window.location.href='{$url}'"
                    ];
                },
                'columns' => [
                  ['class' => 'yii\grid\SerialColumn'],
                  'item_name',
                  'item_price:currency',
                  'qty',
                  [
                      'label' => 'Extra Options',
                      'value' => function ($data) {
                          $extraOptions = '';

                          foreach ($data->orderItemExtraOptions as $key => $extraOption) {

                              if ($key == 0)
                                  $extraOptions .= $extraOption['extra_option_name'];
                              else
                                  $extraOptions .= ', ' . $extraOption['extra_option_name'];
                          }

                          return $extraOptions;
                      },
                      'format' => 'raw'
                  ],
                ],
                'layout' => '{summary}{items}{pager}',
                'tableOptions' => ['class' => 'table data-list-view'],
            ]);
            ?>

        </div>
        <!-- DataTable ends -->

      </section>
    <!-- Data list view end -->
