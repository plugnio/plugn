<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\CustomerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['restaurant_uuid'] = $storeUuid;

$this->title = 'Customers';
$this->params['breadcrumbs'][] = $this->title;

$js = "
$(function () {
  $('.summary').insertAfter('.top');
});
";


$this->registerJs($js);


?>


<section id="data-list-view" class="data-list-view-header">

  <?php if ($dataProvider->getCount() > 0) { ?>

  <!-- Data list view starts -->
  <div class="action-btns d-none">
      <div class="btn-dropdown mr-1 mb-1">
          <div class="btn-group dropdown actions-dropodown">
            <?= Html::a('Add customer', ['create', 'storeUuid' => $storeUuid], ['class' => 'btn btn-primary']) ?>
          </div>
      </div>
  </div>

    <!-- DataTable starts -->
    <div class="table-responsive">

        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'rowOptions' => function($model) {
                $url = Url::to(['customer/view', 'id' => $model->customer_id, 'storeUuid' => $model->restaurant_uuid]);

                return [
                    'onclick' => "window.location.href='{$url}'"
                ];
            },
              'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                  'customer_name',
                  [
                      'attribute' => 'customer_phone_number',
                      "format" => "raw",
                      "value" => function($model) {
                          return '<a href="tel:'. $model->customer_phone_number .'"> '. $model->customer_phone_number.' </a>';
                      }
                  ],
                  'customer_email:email',

                  [
                      'label' => 'Number of orders',
                      "format" => "raw",
                      "value" => function($model) {
                          return  $model->getOrders()->count();
                      }
                  ],
                  [
                      'attribute' => 'customer_created_at',
                      "format" => "raw",
                      "value" => function($model) {
                          return date('d M, Y - h:i A', strtotime($model->customer_created_at));
                      }
                  ],
              ],
            'layout' => '{summary}{items}{pager}',
            'tableOptions' => ['class' => 'table data-list-view'],
        ]);
        ?>

    </div>
    <!-- DataTable ends -->

  <?php } else {?>


    <div class="card">
      <div style="padding: 70px 0; text-align: center;">

        <div>
          <img src="https://res.cloudinary.com/plugn/image/upload/v1607881378/emptystate-customer.svg" width="226" alt="" />
        </div>

        <h3>
          Manage customer details
        </h3>

        <p>
          This is where you can manage your customer information and view their purchase history.
        </p>
        <?= Html::a('Add customer', ['create', 'storeUuid' => $storeUuid], ['class' => 'btn btn-primary']) ?>
      </div>
    </div>


  <?php } ?>

</section>
<!-- Data list view end -->
