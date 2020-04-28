<?php

use common\models\Restaurant;
use yii\helpers\Html;
use common\models\Order;

/* @var $this yii\web\View */

$this->params['restaurant_uuid'] = $restaurant_model->restaurant_uuid;

$this->title = $restaurant_model->name;

?>


<div class="content">
    <div class="container-fluid">

      <section class="content">
        <div class="container-fluid">
          <!-- Small boxes (Stat box) -->
          <div class="row">
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-info">
                <div class="inner">
                  <h3><?= $new_orders ?></h3>

                  <p>New Orders</p>
                </div>
                <div class="icon">
                  <i class="ion ion-bag"></i>
                </div>
                <?=
                      Html::a('More info' .  '<i style="margin-left: 10px;" class="fas fa-arrow-circle-right"></i>', ['order/index', 'restaurantUuid' => $restaurant_model->restaurant_uuid],['class' => 'small-box-footer']);
                ?>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-success">
                <div class="inner">
                  <h3><?= $total_orders ?></h3>

                  <p>Total Orders</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <?=
                    Html::a('More info' .  '<i style="margin-left: 10px;" class="fas fa-arrow-circle-right"></i>', ['order/index', 'restaurantUuid' => $restaurant_model->restaurant_uuid],['class' => 'small-box-footer']);
                ?>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-warning">
                <div class="inner">
                  <h3><?= $total_customers ?> </h3>

                  <p>Total Customers</p>
                </div>
                <div class="icon">
                  <i class="ion ion-person-add"></i>
                </div>
                <?=
                    Html::a('More info' .  '<i style="margin-left: 10px;" class="fas fa-arrow-circle-right"></i>', ['customer/index', 'restaurantUuid' => $restaurant_model->restaurant_uuid],['class' => 'small-box-footer']);
                ?>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-danger">
                <div class="inner">
                  <h3>    <?=  \Yii::$app->formatter->asCurrency($total_earnings ? $total_earnings : 0)   ?></h3>

                  <p>Total Earnings</p>
                </div>
                <div class="icon">
                  <i class="fas fa-money-bill"></i>
                </div>
                 <?=
                    Html::a('More info' .  '<i style="margin-left: 10px;" class="fas fa-arrow-circle-right"></i>', ['order/index', 'restaurantUuid' => $restaurant_model->restaurant_uuid],['class' => 'small-box-footer']);
                ?>
              </div>
            </div>
            <!-- ./col -->
          </div>
          <!-- /.row -->

        </div><!-- /.container-fluid -->
      </section>
        <div class="row">
            <div class="col-md-8">
                <?php if ($orders > 0) { ?>
                    <div class="site-index">
                        <!-- TABLE: LATEST ORDERS -->
                        <div class="card">
                            <div class="card-header border-transparent">
                                <h3 class="card-title">Incoming orders</h3>

                                <div class="card-tools">
                                       <?=

                         Html::button('Play sound for new orders', [ 'class' => 'btn btn-primary', 'onclick' => '' ]);

                                        ?>
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>

                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table m-0">
                                        <thead>
                                            <tr>
                                                <th>Order ID</th>
                                                <th>Customer name</th>
                                                <th>Status</th>
                                                <th>Created At</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($orders as $order) {
                                                ?>
                                                <tr>
                                                    <td>
                                                        <?=
                                                        Html::a('#'. $order->order_uuid, ['order/view', 'id' => $order->order_uuid, 'restaurantUuid' => $order->restaurant_uuid])
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?= $order->customer_name ?>
                                                    </td>
                                                    <td>

                                                        <?php
                                                        $options = ['class' => ''];

                                                        if ($order->order_status == Order::STATUS_PENDING) {
                                                            Html::addCssClass($options, ['badge badge-warning']);
                                                        } else if ($order->order_status == Order::STATUS_BEING_PREPARED) {
                                                            Html::addCssClass($options, ['badge badge-warning']);
                                                        } else if ($order->order_status == Order::STATUS_OUT_FOR_DELIVERY) {
                                                            Html::addCssClass($options, ['badge badge-primary']);
                                                        } else if ($order->order_status == Order::STATUS_COMPLETE) {
                                                            Html::addCssClass($options, ['badge badge-success']);
                                                        } else if ($order->order_status == Order::STATUS_REFUNDED) {
                                                            Html::addCssClass($options, ['badge badge-info']);
                                                        } else if ($order->order_status == Order::STATUS_CANCELED) {
                                                            Html::addCssClass($options, ['badge badge-danger']);
                                                        }

                                                        echo Html::tag('span', $order->orderStatus, $options);

                                                        ?>
                                                    </td>
                                                    <td>
                                                        <div class="sparkbar" data-color="#00a65a" data-height="20">
                                                        <?= Yii::$app->formatter->asRelativeTime($order->order_created_at); ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php   }  ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer clearfix">
                                <?=
                                  Html::a('View All Orders', ['order/index', 'id' => $order->order_uuid, 'restaurantUuid' => $order->restaurant_uuid], ['class' => 'btn btn-sm btn-secondary float-right'])
                                ?>
                            </div>
                            <!-- /.card-footer -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <?php } ?>

            </div>

            <div class="col-md-4">
                <div class="jumbotron">
                <?php if ($restaurant_model->restaurant_status == Restaurant::RESTAURANT_STATUS_CLOSE) { ?>
                        <h3>Your restaurant is closed!</h3>
                        <p>

                        <?=
                          Html::a('Open', ['promote-to-open', 'id' => $restaurant_model->restaurant_uuid], ['class' => 'btn btn-success']);
                        ?>

                        </p>
                        <?php
                    } else if ($restaurant_model->restaurant_status == Restaurant::RESTAURANT_STATUS_OPEN) {
                        ?>
                        <h3>Your restaurant is open!</h3>
                        <p>
                            <?=
                            Html::a('Close', ['promote-to-close', 'id' => $restaurant_model->restaurant_uuid], ['class' => 'btn btn-danger']);
                            ?>
                        </p>
                        <?php
                    }
                    ?>

                </div>
            </div>
        </div>
    </div>
</div>
