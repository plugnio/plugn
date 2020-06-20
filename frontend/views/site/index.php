<?php

use common\models\Restaurant;
use yii\helpers\Html;
use common\models\Order;
use common\models\AgentAssignment;

/* @var $this yii\web\View */

$this->params['restaurant_uuid'] = $restaurant_model->restaurant_uuid;


// $js = "
// let today = $('#today');
// let thisWeek = $('#thisWeek');
// let thisMonth = $('#thisMonth');
//
//
// today.click(function(){
//   console.log('today');
//   $('#todayData').show();
//   $('#thisWeekData').hide();
//   $('#thisMonthData').hide();
// });
//
// thisWeek.click(function(){
//     console.log('week');
//   $('#todayData').hide();
//   $('#thisWeekData').show();
//   $('#thisMonthData').hide();
// });
//
// thisMonth.click(function(){
//     console.log('month');
//   $('#todayData').hide();
//   $('#thisWeekData').hide();
//   $('#thisMonthData').show();
// });
//
//
// ";
//

 $data = [1];

?>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>


<!-- <script src="https://code.jquery.com/jquery-1.11.0.min.js"></script> -->
<script type="text/javascript">
// pass PHP variable declared above to JavaScript variable


$(window).on('load', function () {

      var primary = '#7367F0';
      var success = '#28C76F';
      var danger = '#EA5455';
      var warning = '#FF9F43';

      var soldItemsChartOptions = {
        chart: {
          height: 100,
          type: 'area',
          toolbar: {
            show: false,
          },
          sparkline: {
            enabled: true
          },
          grid: {
            show: false,
            padding: {
              left: 0,
              right: 0
            }
          },
        },
        colors: [danger],
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: 'smooth',
          width: 2.5
        },
        fill: {
          type: 'gradient',
          gradient: {
            shadeIntensity: 0.9,
            opacityFrom: 0.7,
            opacityTo: 0.5,
            stops: [0, 80, 100]
          }
        },
        series: [{
          name: 'Items',
          data: <?=  json_encode($sold_item_chart_data) ?>,
        }],

        xaxis: {
          labels: {
            show: false,
          },
          axisBorder: {
            show: false,
          }
        },
        yaxis: [{
          y: 0,
          offsetX: 0,
          offsetY: 0,
          padding: { left: 0, right: 0 },
        }],
        tooltip: {
          x: { show: false }
        },
      }
      var soldItemsChart = new ApexCharts(
        document.querySelector('#sold-item-chart'),
        soldItemsChartOptions
      );

      var orderReceivedChartOptions = {
        chart: {
          height: 100,
          type: 'area',
          toolbar: {
            show: false,
          },
          sparkline: {
            enabled: true
          },
          grid: {
            show: false,
            padding: {
              left: 0,
              right: 0
            }
          },
        },
        colors: [warning],
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: 'smooth',
          width: 2.5
        },
        fill: {
          type: 'gradient',
          gradient: {
            shadeIntensity: 0.9,
            opacityFrom: 0.7,
            opacityTo: 0.5,
            stops: [0, 80, 100]
          }
        },
        series: [{
          name: 'Customers',
          data: <?=  json_encode($orders_received_chart_data) ?>,
        }],

        xaxis: {
          labels: {
            show: false,
          },
          axisBorder: {
            show: false,
          }
        },
        yaxis: [{
          y: 0,
          offsetX: 0,
          offsetY: 0,
          padding: { left: 0, right: 0 },
        }],
        tooltip: {
          x: { show: false }
        },
      }
      var orderReceivedChart = new ApexCharts(
        document.querySelector('#orders-recevied-chart'),
        orderReceivedChartOptions
      );

      var revenueGeneratedChartOptions = {
        chart: {
          height: 100,
          type: 'area',
          toolbar: {
            show: false,
          },
          sparkline: {
            enabled: true
          },
          grid: {
            show: false,
            padding: {
              left: 0,
              right: 0
            }
          },
        },
        colors: [success],
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: 'smooth',
          width: 2.5
        },
        fill: {
          type: 'gradient',
          gradient: {
            shadeIntensity: 0.9,
            opacityFrom: 0.7,
            opacityTo: 0.5,
            stops: [0, 80, 100]
          }
        },
        series: [{
          name: 'Orders',
          data: <?=  json_encode($revenue_generated_chart_data) ?>,
        }],

        xaxis: {
          labels: {
            show: false,
          },
          axisBorder: {
            show: false,
          }
        },
        yaxis: [{
          y: 0,
          offsetX: 0,
          offsetY: 0,
          padding: { left: 0, right: 0 },
        }],
        tooltip: {
          x: { show: false }
        },
      }
      var revenueGeneratedChart = new ApexCharts(
        document.querySelector('#revenue-generated-chart'),
        revenueGeneratedChartOptions
      );


      var customerGainedChartOptions = {
        chart: {
          height: 100,
          type: 'area',
          toolbar: {
            show: false,
          },
          sparkline: {
            enabled: true
          },
          grid: {
            show: false,
            padding: {
              left: 0,
              right: 0
            }
          },
        },
        colors: [primary],
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: 'smooth',
          width: 2.5
        },
        fill: {
          type: 'gradient',
          gradient: {
            shadeIntensity: 0.9,
            opacityFrom: 0.7,
            opacityTo: 0.5,
            stops: [0, 80, 100]
          }
        },
        series: [{
          name: 'Customers',
          data: <?=  json_encode($customer_chart_data) ?>,
        }],

        xaxis: {
          labels: {
            show: false,
          },
          axisBorder: {
            show: false,
          }
        },
        yaxis: [{
          y: 0,
          offsetX: 0,
          offsetY: 0,
          padding: { left: 0, right: 0 },
        }],
        tooltip: {
          x: { show: false }
        },
      }
      var customerGainedChart = new ApexCharts(
        document.querySelector('#customer-gained-chart'),
        customerGainedChartOptions
      );


      customerGainedChart.render();
      revenueGeneratedChart.render();
      soldItemsChart.render();
      orderReceivedChart.render();


});



</script>


<!-- Dashboard Ecommerce Starts -->
<section id="dashboard-ecommerce">
    <div class="row">
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-header d-flex flex-column align-items-start pb-0">
                    <div class="avatar bg-rgba-primary p-50 m-0">
                        <div class="avatar-content">
                            <i class="feather icon-users text-primary font-medium-5"></i>
                        </div>
                    </div>
                    <h2 class="text-bold-700 mt-1">
                        <?= $customers_gained ?>
                    </h2>
                    <p class="mb-0">Customer Gained</p>
                </div>
                <div class="card-content">
                    <div id="customer-gained-chart"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-header d-flex flex-column align-items-start pb-0">
                    <div class="avatar bg-rgba-success p-50 m-0">
                        <div class="avatar-content">
                            <i class="feather icon-credit-card text-success font-medium-5"></i>
                        </div>
                    </div>
                    <h2 class="text-bold-700 mt-1">
                        <?= $revenue_generated ? Yii::$app->formatter->asCurrency($revenue_generated, 'KWD', [NumberFormatter::MIN_FRACTION_DIGITS => 3, NumberFormatter::MAX_FRACTION_DIGITS => 5]) : 0 ?>

                    </h2>
                    <p class="mb-0">Revenue Generated</p>
                </div>
                <div class="card-content">
                    <div id="revenue-generated-chart"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-header d-flex flex-column align-items-start pb-0">
                    <div class="avatar bg-rgba-danger p-50 m-0">
                        <div class="avatar-content">
                            <i class="feather icon-shopping-cart text-danger font-medium-5"></i>
                        </div>
                    </div>
                    <h2 class="text-bold-700 mt-1">
                        <?= $sold_item ?>
                    </h2>
                    <p class="mb-0">Sold Items</p>
                </div>
                <div class="card-content">
                    <div id="sold-item-chart"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-header d-flex flex-column align-items-start pb-0">
                    <div class="avatar bg-rgba-warning p-50 m-0">
                        <div class="avatar-content">
                            <i class="feather icon-package text-warning font-medium-5"></i>
                        </div>
                    </div>
                    <h2 class="text-bold-700 mt-1">
                        <?= $orders_received ?>
                    </h2>
                    <p class="mb-0">Orders Received</p>
                </div>
                <div class="card-content">
                    <div id="orders-recevied-chart"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class=" col-12">
            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-header">
                            <h4 class="mb-0">Incoming Orders</h4>
                        </div>

                        <div class="card-content">
                            <div class="table-responsive mt-1">

                                <table class="table table-hover-animation mb-0">
                                    <thead>
                                        <tr>
                                            <th>ORDER</th>
                                            <th>STATUS</th>
                                            <th>CUSTOMER NAME</th>
                                            <th>Created At</th>

                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        foreach ($incoming_orders as $order) {
                                            ?>
                                            <tr>
                                                <td>
                                                    <?=
                                                    Html::a('#' . $order->order_uuid, ['order/view', 'id' => $order->order_uuid, 'restaurantUuid' => $order->restaurant_uuid])
                                                    ?>
                                                </td>

                                                <td>

                                                    <?php
                                                    $options = ['class' => ''];

                                                    if ($order->order_status == Order::STATUS_PENDING) {
                                                        Html::addCssClass($options, ['fa fa-circle font-small-3 text-warning mr-50']);
                                                    } elseif ($order->order_status == Order::STATUS_BEING_PREPARED) {
                                                        Html::addCssClass($options, ['fa fa-circle font-small-3 text-primary mr-50']);
                                                    } elseif ($order->order_status == Order::STATUS_OUT_FOR_DELIVERY) {
                                                        Html::addCssClass($options, ['fa fa-circle font-small-3 text-info mr-50']);
                                                    } elseif ($order->order_status == Order::STATUS_COMPLETE) {
                                                        Html::addCssClass($options, ['fa fa-circle font-small-3 success-info mr-50']);
                                                    } elseif ($order->order_status == Order::STATUS_REFUNDED) {
                                                        Html::addCssClass($options, ['fa fa-circle font-small-3 text-danger mr-50']);
                                                    } elseif ($order->order_status == Order::STATUS_CANCELED) {
                                                        Html::addCssClass($options, ['fa fa-circle font-small-3 text-danger mr-50']);
                                                    }

                                                    echo Html::tag('i', '', $options) . $order->orderStatus
                                                    ?>

                                                </td>
                                                <td>
    <?= $order->customer_name ?>
                                                </td>

                                                <td>
                                                    <div class="sparkbar" data-color="#00a65a" data-height="20">
    <?= Yii::$app->formatter->asRelativeTime($order->order_created_at); ?>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php }
                                        ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-end">
                    <h4 class="mb-0">Restaurant Status</h4>
                </div>
                <div class="card-content">
                    <div class="card-body px-0 pb-0">

                        <div class="col-12">
                            <div class="form-group">
<?php if ($restaurant_model->restaurant_status == Restaurant::RESTAURANT_STATUS_CLOSE) { ?>

                                    <p style="font-size: 20px; margin-top: 65px; padding-left: 20px; margin-bottom: 65px; text-align: center; padding-right: 20px;">
                                        Your store is open you can accept orders!
                                    </p>

                                    <?=
                                    Html::a('Open your store', ['promote-to-open', 'id' => $restaurant_model->restaurant_uuid], ['class' => 'btn mb-1 btn-success btn-lg btn-block']);
                                    ?>

                                    <?php } elseif ($restaurant_model->restaurant_status == Restaurant::RESTAURANT_STATUS_OPEN) {
                                    ?>
                                    <p style="font-size: 20px; margin-top: 65px; padding-left: 20px; margin-bottom: 65px; text-align: center; padding-right: 20px;">
                                        Your store is open you can accept orders!
                                    </p>

                                    <?=
                                    Html::a('Close your store', ['promote-to-close', 'id' => $restaurant_model->restaurant_uuid], ['class' => 'btn mb-1 btn-danger btn-lg btn-block']);
                                    ?>

<?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

</section>
<!-- Dashboard Ecommerce ends -->
