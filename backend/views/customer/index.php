<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\models\Order;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\CustomerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['restaurant_uuid'] = $searchModel->restaurant_uuid;

$this->title = 'Customers';
$this->params['breadcrumbs'][] = $this->title;

?>

<section id="data-list-view" class="data-list-view-header">

  <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

  <!-- Data list view starts -->
  <!-- <div class="action-btns d-none">
      <div class="btn-dropdown mr-1 mb-1">
          <div class="btn-group dropdown actions-dropodown"> -->
            <?php
            // Html::a('Add customer', ['create', 'storeUuid' => $storeUuid], ['class' => 'btn btn-primary']);
             ?>
          <!-- </div>
      </div>
  </div> -->

    <!-- DataTable starts -->
    <div class="table-responsive">

        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
              'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                  'customer_name',
                  [
                      'attribute' => 'customer_phone_number',
                      "format" => "raw",
                      "value" => function($model) {
                          return '<a href="tel:'. $model->customer_phone_number .'"> '. str_replace(' ', '', $model->customer_phone_number) .' </a>';
                      }
                  ],
                  'customer_email:email',
                  'ip_address',
                  [
                      'label' => 'Number of orders',
                      "format" => "raw",
                      "value" => function($model) {
                        return $model->getOrders()
                            ->activeOrders()
                            ->count();
                      }
                  ],
                  // [
                  //     'attribute' => 'Total spent',
                  //     "format" => "raw",
                  //     "value" => function($model) {
                  //       return $model->totalSpent;
                  //
                  //         $total_spent = \Yii::$app->formatter->asDecimal($total_spent ? $total_spent : 0 , 3);
                  //         return  Yii::$app->formatter->asCurrency($total_spent ? $total_spent : 0, $model->currency->code) ;
                  //
                  //     }
                  // ],
                  [
                      'attribute' => 'customer_created_at',
                      "format" => "raw",
                      "value" => function($model) {
                          return date('d M, Y - h:i A', strtotime($model->customer_created_at));
                      }
                  ],
                  ['class' => 'yii\grid\ActionColumn'],
              ],
        ]);
        ?>

    </div>
    <!-- DataTable ends -->

</section>
<!-- Data list view end -->
