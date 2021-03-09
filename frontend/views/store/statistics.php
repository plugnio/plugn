<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Restaurant;

/* @var $this yii\web\View */
/* @var $model common\models\Restaurant */

$this->params['restaurant_uuid'] = $model->restaurant_uuid;

$this->title = 'Overview dashboard';
$this->params['breadcrumbs'][] = $this->title;
// \yii\web\YiiAsset::register($this);


$currencyCode = $model->currency->code;

?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>


<!-- <script src="https://code.jquery.com/jquery-1.11.0.min.js"></script> -->
<script type="text/javascript">

  var currency_code = "<?= $currencyCode ?>";


    $(document).ready(function () {


        var primary = '#7367F0'
        var success = '#28C76F'
        var danger = '#EA5455'
        var warning = '#FF9F43'
        var info = '#00cfe8'
        var label_color_light = '#dae1e7';

        var themeColors = [primary, success, danger, warning, info];

        // RTL Support
        var yaxis_opposite = false;
        if ($('html').data('textdirection') == 'rtl') {
            yaxis_opposite = true;
        }

        var revenueChartOptions = {
            chart: {
                height: 350,
                type: 'line',
                zoom: {
                    enabled: false
                }
            },
            colors: themeColors,
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'straight'
            },
            series: [{
                    name: "Revenue",
                    data: <?= json_encode($revenue_generated_chart_data) ?>,
                }],
            grid: {
                row: {
                    colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                    opacity: 0.5
                },
            },
            xaxis: {
                categories: <?= json_encode($months) ?>,
            },
            yaxis: {
                tickAmount: 5,
                opposite: yaxis_opposite
            },
            tooltip: {
                y: {
                  formatter: function (val) {
                    return  currency_code +  ' ' + val  ;
                  }
                }
          }
        }

        var revenueChart = new ApexCharts(
                document.querySelector("#revenue-generated-chart"),
                revenueChartOptions
                );

        revenueChart.render();

        var orderReceivedChartOptions = {
            chart: {
                height: 350,
                type: 'line',
                zoom: {
                    enabled: false
                }
            },
            colors: themeColors,
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'straight'
            },
            series: [{
                    name: "Orders",
                    data: <?= json_encode($order_recevied_chart_data) ?>,
                }],
            grid: {
                row: {
                    colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                    opacity: 0.5
                },
            },
            xaxis: {
                categories: <?= json_encode($months) ?>,
            },
            yaxis: {
                tickAmount: 5,
                opposite: yaxis_opposite
            }
        }

        var customerGainedChartOptions = {
            chart: {
                height: 350,
                type: 'line',
                zoom: {
                    enabled: false
                }
            },
            colors: themeColors,
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'straight'
            },
            series: [{
                    name: "Customers",
                    data: <?= json_encode($customer_gained_chart_data) ?>,
                }],
            grid: {
                row: {
                    colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                    opacity: 0.5
                },
            },
            xaxis: {
                categories: <?= json_encode($months) ?>,
            },
            yaxis: {
                tickAmount: 5,
                opposite: yaxis_opposite
            }
        }


        var ordersReceviedChart = new ApexCharts(
                document.querySelector("#orders-recevied-chart"),
                orderReceivedChartOptions
                );

        ordersReceviedChart.render();


        var customerGainedChart = new ApexCharts(
                document.querySelector("#customers-gained-chart"),
                customerGainedChartOptions
                );

        customerGainedChart.render();

        var soldItemsChartOptions = {
            chart: {
                height: 350,
                type: 'bar',
            },
            colors: themeColors,
            plotOptions: {
                bar: {
                    horizontal: true,
                }
            },
            dataLabels: {
                enabled: false
            },
            series: [{
                    name: "Sold items",
                    data: <?= json_encode($number_of_sold_items_chart_data) ?>
                }],
            xaxis: {
                categories: <?= json_encode($most_selling_items_chart_data) ?>,
                tickAmount: 5
            },
            yaxis: {
                opposite: yaxis_opposite
            }
        }


        var ordersReceviedChart = new ApexCharts(
                document.querySelector("#sold-items-chart"),
                soldItemsChartOptions
                );

        ordersReceviedChart.render();
    });



</script>

<div class="content-body">


    <!-- apex charts section start -->
    <section id="apexchart">
        <div class="row">
            <!-- Revenue Generated -->
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h4 class="card-title">Revenue Generated</h4>

                          <!-- <button type="button" style=" margin-top: 15px;" class="btn btn-success mr-1 mb-1 waves-effect waves-light"><i class="fa fa-file-excel-o"></i></button> -->
                        </div>
                    </div>

                    <div class="card-content">
                        <div class="card-body">
                            <div id="revenue-generated-chart"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Orders Received -->
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h4 class="card-title">Orders Received</h4>

                          <!-- <button type="button" style=" margin-top: 15px;" class="btn btn-success mr-1 mb-1 waves-effect waves-light"><i class="fa fa-file-excel-o"></i></button> -->
                        </div>
                    </div>

                    <div class="card-content">
                        <div class="card-body">
                            <div id="orders-recevied-chart"></div>
                        </div>
                    </div>
                </div>
            </div>



            <?php

            if(count($most_selling_items_chart_data)){ ?>
            <!-- Sold Items -->
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Sold Items</h4>

                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div id="sold-items-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>

            <!--Customer Gained-->
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h4 class="card-title">Customers Gained</h4>

                          <!-- <button type="button" style=" margin-top: 15px;" class="btn btn-success mr-1 mb-1 waves-effect waves-light"><i class="fa fa-file-excel-o"></i></button> -->
                        </div>
                    </div>

                    <div class="card-content">
                        <div class="card-body">
                            <div id="customers-gained-chart"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>



</div>

</section>
<!-- // Apex charts section end -->

</div>
