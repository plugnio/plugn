<?php

use common\models\Restaurant;
use yii\helpers\Html;
use common\models\Order;
use common\models\AgentAssignment;

/* @var $this yii\web\View */

$this->params['restaurant_uuid'] = $restaurant_model->restaurant_uuid;

$this->title = $restaurant_model->name;

?>
<script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>

<script type = "text/javascript">

    var soundForNewOrders = new Audio("data:audio/wav;base64,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qJrx4KOQjahZxWbcZgztj2c49nKmkId44S71j0c8eV9yDK6uPRzx5X18eDvjvQ6yKo9ZSS6l//8elePK/Lf//IInrOF/FvDoADYAGBMGb7FtErm5MXMlmPAJQVgWta7Zx2go+8xJ0UiCb8LHHdftWyLJE0QIAIsI+UbXu67dZMjmgDGCGl1H+vpF4NSDckSIkk7Vd+sxEhBQMRU8j/12UIRhzSaUdQ+rQU5kGeFxm+hb1oh6pWWmv3uvmReDl0UnvtapVaIzo1jZbf/pD6ElLqSX+rUmOQNpJFa/r+sa4e/pBlAABoAAAAA3CUgShLdGIxsY7AUABPRrgCABdDuQ5GC7DqPQCgbbJUAoRSUj+NIEig0YfyWUho1VBBBA//uQZB4ABZx5zfMakeAAAAmwAAAAF5F3P0w9GtAAACfAAAAAwLhMDmAYWMgVEG1U0FIGCBgXBXAtfMH10000EEEEEECUBYln03TTTdNBDZopopYvrTTdNa325mImNg3TTPV9q3pmY0xoO6bv3r00y+IDGid/9aaaZTGMuj9mpu9Mpio1dXrr5HERTZSmqU36A3CumzN/9Robv/Xx4v9ijkSRSNLQhAWumap82WRSBUqXStV/YcS+XVLnSS+WLDroqArFkMEsAS+eWmrUzrO0oEmE40RlMZ5+ODIkAyKAGUwZ3mVKmcamcJnMW26MRPgUw6j+LkhyHGVGYjSUUKNpuJUQoOIAyDvEyG8S5yfK6dhZc0Tx1KI/gviKL6qvvFs1+bWtaz58uUNnryq6kt5RzOCkPWlVqVX2a/EEBUdU1KrXLf40GoiiFXK///qpoiDXrOgqDR38JB0bw7SoL+ZB9o1RCkQjQ2CBYZKd/+VJxZRRZlqSkKiws0WFxUyCwsKiMy7hUVFhIaCrNQsKkTIsLivwKKigsj8XYlwt/WKi2N4d//uQRCSAAjURNIHpMZBGYiaQPSYyAAABLAAAAAAAACWAAAAApUF/Mg+0aohSIRobBAsMlO//Kk4soosy1JSFRYWaLC4qZBYWFRGZdwqKiwkNBVmoWFSJkWFxX4FFRQWR+LsS4W/rFRb/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////VEFHAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAU291bmRib3kuZGUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAMjAwNGh0dHA6Ly93d3cuc291bmRib3kuZGUAAAAAAAAAACU=");
        
    function enableSoundForNewOrders() {
        document.getElementById("play-btn").value = true;
        document.getElementById("stop-btn").value = false
                
        document.getElementById("play-sound-section").style.display = "none";
        document.getElementById("stop-sound-section").style.display = "block";
    }


    function disableSoundForNewOrders() {
        document.getElementById("stop-btn").value = true;
        document.getElementById("play-btn").value = false
        
        document.getElementById("play-sound-section").style.display = "block";
        document.getElementById("stop-sound-section").style.display = "none";
    }

    async function CheckPendingOrders() {
        const url = <?= "'" . Yii::$app->params['apiEndpoint']  .  '/v1/order/check-for-pending-orders/' .  $restaurant_model->restaurant_uuid . "'" ?>;
        fetch(url)
            .then(res => res.json())
            .then(data => {

                $("#new-order-table").load(<?= "'" . yii\helpers\Url::to(['site/check-for-new-orders', 'restaurant_uuid' => $restaurant_model->restaurant_uuid]) . "'" ?>);
       
                if (document.getElementById("play-btn").value == 'true' && document.getElementById("stop-btn").value == 'false') {
                    console.log('play');
                     soundForNewOrders.play();
                } else if (!data || document.getElementById("stop-btn").value == 'true' && document.getElementById("play-btn").value == 'false') {
                    console.log('pause');
                    soundForNewOrders.pause();
                }
            }).catch(err => {
                console.error('Error: ', err);
            });
    }

    setInterval(function() {
        CheckPendingOrders();
    }, 1000);

    CheckPendingOrders();

</script>

<div class="content">
    <div class="container-fluid">

      <section class="content">
        <div class="container-fluid">
          <!-- Small boxes (Stat box) -->
          <div class="row">
              
                   <?php if (AgentAssignment::isOwner($restaurant_model->restaurant_uuid)) { ?>

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
                  <h3>    <?=  \Yii::$app->formatter->asCurrency($total_revenue ? $total_revenue : 0)   ?></h3>

                  <p>Total Revenue</p>
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
            
                   <?php } else { ?>
                       <div class="col-lg-4 col-7">
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
            <div class="col-lg-4 col-7">
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
            <div class="col-lg-4 col-7">
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
      
                   <?php } ?>
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
                                    <div id="play-sound-section">
                                       <?=
                                           Html::button('Play sound for new orders', [ 'class' => 'btn btn-success','id'=>'play-btn', 'onclick' => 'enableSoundForNewOrders()' ]);
                                        ?> 
                                    </div>
                                    <div id="stop-sound-section" style="display: none">
                                       <?=
                                           Html::button('Stop sound for new orders', [ 'class' => 'btn btn-danger','id'=>'stop-btn', 'onclick' => 'disableSoundForNewOrders()' ]);
                                        ?> 
                                    </div>
                                       
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
                                        <tbody id="new-order-table">
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
                                  Html::a('View All Orders', ['order/index', 'restaurantUuid' => $restaurant_model->restaurant_uuid ], ['class' => 'btn btn-sm btn-secondary float-right'])
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
                        <h3>Your store is closed you can't accept orders!</h3>
                        <p>

                        <?=
                          Html::a('Open your store', ['promote-to-open', 'id' => $restaurant_model->restaurant_uuid], ['class' => 'btn btn-success']);
                        ?>

                        </p>
                        <?php
                    } else if ($restaurant_model->restaurant_status == Restaurant::RESTAURANT_STATUS_OPEN) {
                        ?>
                        <h3>Your store is open you can accept orders!</h3>
                        <p>
                            <?=
                            Html::a('Close your store', ['promote-to-close', 'id' => $restaurant_model->restaurant_uuid], ['class' => 'btn btn-danger']);
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
