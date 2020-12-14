<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Order;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->params['restaurant_uuid'] = $restaurant_model->restaurant_uuid;

$this->title = 'Drafts';
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
                <?= Html::a('<i class="feather icon-plus"></i> Add New', ['create', 'storeUuid' => $restaurant_model->restaurant_uuid], ['class' => 'btn btn-outline-primary']) ?>
            </div>
        </div>
    </div>


    <?php
    // echo $this->render('_search', ['model' => $searchModel, 'restaurant_uuid' => $restaurant_model->restaurant_uuid]);
    ?>


    <!-- DataTable starts -->
    <div class="table-responsive">

        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'rowOptions' => function($model) {
                $url = Url::to(['order/view', 'id' => $model->order_uuid, 'storeUuid' => $model->restaurant_uuid]);

                return [
                    'onclick' => "window.location.href='{$url}'"
                ];
            },
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'label' => 'Order ID',
                    "format" => "raw",
                    "value" => function($model) {
                        return '#' . $model->order_uuid;
                    }
                ],
                [
                    'attribute' => 'customer_name',
                    'format' => 'raw',
                    'value' => function ($data) {
                        if ($data->customer_id)
                            return Html::a($data->customer->customer_name, ['customer/view', 'id' => $data->customer_id, 'storeUuid' => $data->restaurant_uuid]);
                    },
                    'visible' => function ($data) {
                        return $data->customer_id ? true : false;
                    },
                ],
                [
                    'label' => 'Payment',
                    "format" => "raw",
                    "value" => function($data) {
                        if ($data->payment_uuid)
                            return $data->payment->payment_current_status;
                        else
                            return $data->paymentMethod->payment_method_name;
                    },
                ],
                'total_price:currency',
                'order_created_at:datetime',
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
          <img src="https://res.cloudinary.com/plugn/image/upload/v1607879689/empty-state.svg" width="226" alt="" />
        </div>

        <h3>
          Manually create orders and invoices
        </h3>

        <p>
          Use draft orders to take orders over the phone, email invoices to customers, and collect payments.
        </p>
        <?= Html::a('Create draft order', ['create', 'storeUuid' => $restaurant_model->restaurant_uuid], ['class' => 'btn btn-primary']) ?>
      </div>
    </div>


  <?php } ?>

</section>
<!-- Data list view end -->
