<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\VoucherSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->params['restaurant_uuid'] = $restaurant_model->restaurant_uuid;

$this->title = 'Vouchers';
$this->params['breadcrumbs'][] = $this->title;
?>
<section id="data-list-view" class="data-list-view-header">

  <?php if($dataProvider->getCount() == 0 ){  ?>
    <div style="padding-left:14px">
    <?= Html::a('<i class="feather icon-plus"></i> Add New', ['create', 'restaurantUuid' => $restaurant_model->restaurant_uuid], ['class' => 'btn btn-outline-primary','style'=>'    padding: 0.85rem 1.7rem;']) ?>
  </div>
  <?php } ?>

  <!-- Data list view starts -->
  <div class="action-btns d-none">
      <div class="btn-dropdown mr-1 mb-1">
          <div class="btn-group dropdown actions-dropodown">
            <?= Html::a('<i class="feather icon-plus"></i> Add New', ['create', 'restaurantUuid' => $restaurant_model->restaurant_uuid], ['class' => 'btn btn-outline-primary']) ?>
          </div>
      </div>
  </div>

  <div class="table-responsive">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'rowOptions' => function($model) {
            $url = Url::to(['voucher/update', 'id' => $model->voucher_id, 'restaurantUuid' => $model->restaurant_uuid]);

            return [
                'onclick' => "window.location.href='{$url}'"
            ];
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'code',
            'restaurant_uuid',
            'max_redemption',
            'discount_amount',
            'minimum_order_amount',
            'voucher_status',
            //'discount_type',
            //'start_at',
            //'expiry_date',
            //'max_redemption',
            //'limit_per_customer',
            //'minimum_order_amount',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</section>
