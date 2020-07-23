<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Voucher;

/* @var $this yii\web\View */
/* @var $model common\models\Voucher */

$this->params['restaurant_uuid'] = $model->restaurant_uuid;

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Vouchers', 'url' => ['index', 'restaurantUuid' =>  $model->restaurant_uuid]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="card">
  <div class="card-body voucher-view">

      <p>
          <?= Html::a('Update', ['update', 'id' => $model->voucher_id, 'restaurantUuid' =>  $model->restaurant_uuid], ['class' => 'btn btn-primary  mr-1 mb-1']) ?>
          <?= Html::a('Delete', ['delete', 'id' => $model->voucher_id, 'restaurantUuid' =>  $model->restaurant_uuid], [
              'class' => 'btn btn-danger  mr-1 mb-1',
              'data' => [
                  'confirm' => 'Are you sure you want to delete this item?',
                  'method' => 'post',
              ],
          ]) ?>
      </p>

      <?= DetailView::widget([
          'model' => $model,
          'attributes' => [
              'title',
              'title_ar',
              'code',
              [
                  'attribute' => 'discount_type',
                  'format' => 'html',
                  'value' => function ($model) {
                      return $model->discount_type == Voucher::DISCOUNT_TYPE_PERCENTAGE ? 'Percentage' : 'Amount';
                  },
              ],
              [
                  'attribute' => 'valid_from',
                  "format" => "raw",
                  "value" => function($model) {
                      return date('Y-m-d', strtotime($model->valid_from));
                  }
              ],
              [
                  'attribute' => 'valid_until',
                  "format" => "raw",
                  "value" => function($model) {
                      return date('Y-m-d', strtotime($model->valid_until));
                  }
              ],
              'max_redemption',
              'limit_per_customer',
              'minimum_order_amount',
          ],
      ]) ?>

  </div>
</div>
