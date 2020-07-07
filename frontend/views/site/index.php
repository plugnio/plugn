<?php

use common\models\Restaurant;
use yii\helpers\Html;
use common\models\Order;
use common\models\AgentAssignment;
use yii\grid\GridView;

/* @var $this yii\web\View */

$this->params['restaurant_uuid'] = $restaurant_model->restaurant_uuid;
$this->title = $restaurant_model->name;


$js = "
 $(document).ready(function(){
    $('#dropdownCustomerGained').html('Last 7 days');
    $('#dropdownRevenueGenerated').html('Last 7 days');
    $('#dropdownSoldItems').html('Last 7 days');
    $('#dropdownOrdersReceived').html('Last 7 days');
});
";
$this->registerJs($js);
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>


<!-- <script src="https://code.jquery.com/jquery-1.11.0.min.js"></script> -->
<script type="text/javascript">



    var soundForNewOrders = new Audio("data:audio/wav;base64,//uQRAAAAWMSLwUIYAAsYkXgoQwAEaYLWfkWgAI0wWs/ItAAAGDgYtAgAyN+QWaAAihwMWm4G8QQRDiMcCBcH3Cc+CDv/7xA4Tvh9Rz/y8QADBwMWgQAZG/ILNAARQ4GLTcDeIIIhxGOBAuD7hOfBB3/94gcJ3w+o5/5eIAIAAAVwWgQAVQ2ORaIQwEMAJiDg95G4nQL7mQVWI6GwRcfsZAcsKkJvxgxEjzFUgfHoSQ9Qq7KNwqHwuB13MA4a1q/DmBrHgPcmjiGoh//EwC5nGPEmS4RcfkVKOhJf+WOgoxJclFz3kgn//dBA+ya1GhurNn8zb//9NNutNuhz31f////9vt///z+IdAEAAAK4LQIAKobHItEIYCGAExBwe8jcToF9zIKrEdDYIuP2MgOWFSE34wYiR5iqQPj0JIeoVdlG4VD4XA67mAcNa1fhzA1jwHuTRxDUQ//iYBczjHiTJcIuPyKlHQkv/LHQUYkuSi57yQT//uggfZNajQ3Vmz+Zt//+mm3Wm3Q576v////+32///5/EOgAAADVghQAAAAA//uQZAUAB1WI0PZugAAAAAoQwAAAEk3nRd2qAAAAACiDgAAAAAAABCqEEQRLCgwpBGMlJkIz8jKhGvj4k6jzRnqasNKIeoh5gI7BJaC1A1AoNBjJgbyApVS4IDlZgDU5WUAxEKDNmmALHzZp0Fkz1FMTmGFl1FMEyodIavcCAUHDWrKAIA4aa2oCgILEBupZgHvAhEBcZ6joQBxS76AgccrFlczBvKLC0QI2cBoCFvfTDAo7eoOQInqDPBtvrDEZBNYN5xwNwxQRfw8ZQ5wQVLvO8OYU+mHvFLlDh05Mdg7BT6YrRPpCBznMB2r//xKJjyyOh+cImr2/4doscwD6neZjuZR4AgAABYAAAABy1xcdQtxYBYYZdifkUDgzzXaXn98Z0oi9ILU5mBjFANmRwlVJ3/6jYDAmxaiDG3/6xjQQCCKkRb/6kg/wW+kSJ5//rLobkLSiKmqP/0ikJuDaSaSf/6JiLYLEYnW/+kXg1WRVJL/9EmQ1YZIsv/6Qzwy5qk7/+tEU0nkls3/zIUMPKNX/6yZLf+kFgAfgGyLFAUwY//uQZAUABcd5UiNPVXAAAApAAAAAE0VZQKw9ISAAACgAAAAAVQIygIElVrFkBS+Jhi+EAuu+lKAkYUEIsmEAEoMeDmCETMvfSHTGkF5RWH7kz/ESHWPAq/kcCRhqBtMdokPdM7vil7RG98A2sc7zO6ZvTdM7pmOUAZTnJW+NXxqmd41dqJ6mLTXxrPpnV8avaIf5SvL7pndPvPpndJR9Kuu8fePvuiuhorgWjp7Mf/PRjxcFCPDkW31srioCExivv9lcwKEaHsf/7ow2Fl1T/9RkXgEhYElAoCLFtMArxwivDJJ+bR1HTKJdlEoTELCIqgEwVGSQ+hIm0NbK8WXcTEI0UPoa2NbG4y2K00JEWbZavJXkYaqo9CRHS55FcZTjKEk3NKoCYUnSQ0rWxrZbFKbKIhOKPZe1cJKzZSaQrIyULHDZmV5K4xySsDRKWOruanGtjLJXFEmwaIbDLX0hIPBUQPVFVkQkDoUNfSoDgQGKPekoxeGzA4DUvnn4bxzcZrtJyipKfPNy5w+9lnXwgqsiyHNeSVpemw4bWb9psYeq//uQZBoABQt4yMVxYAIAAAkQoAAAHvYpL5m6AAgAACXDAAAAD59jblTirQe9upFsmZbpMudy7Lz1X1DYsxOOSWpfPqNX2WqktK0DMvuGwlbNj44TleLPQ+Gsfb+GOWOKJoIrWb3cIMeeON6lz2umTqMXV8Mj30yWPpjoSa9ujK8SyeJP5y5mOW1D6hvLepeveEAEDo0mgCRClOEgANv3B9a6fikgUSu/DmAMATrGx7nng5p5iimPNZsfQLYB2sDLIkzRKZOHGAaUyDcpFBSLG9MCQALgAIgQs2YunOszLSAyQYPVC2YdGGeHD2dTdJk1pAHGAWDjnkcLKFymS3RQZTInzySoBwMG0QueC3gMsCEYxUqlrcxK6k1LQQcsmyYeQPdC2YfuGPASCBkcVMQQqpVJshui1tkXQJQV0OXGAZMXSOEEBRirXbVRQW7ugq7IM7rPWSZyDlM3IuNEkxzCOJ0ny2ThNkyRai1b6ev//3dzNGzNb//4uAvHT5sURcZCFcuKLhOFs8mLAAEAt4UWAAIABAAAAAB4qbHo0tIjVkUU//uQZAwABfSFz3ZqQAAAAAngwAAAE1HjMp2qAAAAACZDgAAAD5UkTE1UgZEUExqYynN1qZvqIOREEFmBcJQkwdxiFtw0qEOkGYfRDifBui9MQg4QAHAqWtAWHoCxu1Yf4VfWLPIM2mHDFsbQEVGwyqQoQcwnfHeIkNt9YnkiaS1oizycqJrx4KOQjahZxWbcZgztj2c49nKmkId44S71j0c8eV9yDK6uPRzx5X18eDvjvQ6yKo9ZSS6l//8elePK/Lf//IInrOF/FvDoADYAGBMGb7FtErm5MXMlmPAJQVgWta7Zx2go+8xJ0UiCb8LHHdftWyLJE0QIAIsI+UbXu67dZMjmgDGCGl1H+vpF4NSDckSIkk7Vd+sxEhBQMRU8j/12UIRhzSaUdQ+rQU5kGeFxm+hb1oh6pWWmv3uvmReDl0UnvtapVaIzo1jZbf/pD6ElLqSX+rUmOQNpJFa/r+sa4e/pBlAABoAAAAA3CUgShLdGIxsY7AUABPRrgCABdDuQ5GC7DqPQCgbbJUAoRSUj+NIEig0YfyWUho1VBBBA//uQZB4ABZx5zfMakeAAAAmwAAAAF5F3P0w9GtAAACfAAAAAwLhMDmAYWMgVEG1U0FIGCBgXBXAtfMH10000EEEEEECUBYln03TTTdNBDZopopYvrTTdNa325mImNg3TTPV9q3pmY0xoO6bv3r00y+IDGid/9aaaZTGMuj9mpu9Mpio1dXrr5HERTZSmqU36A3CumzN/9Robv/Xx4v9ijkSRSNLQhAWumap82WRSBUqXStV/YcS+XVLnSS+WLDroqArFkMEsAS+eWmrUzrO0oEmE40RlMZ5+ODIkAyKAGUwZ3mVKmcamcJnMW26MRPgUw6j+LkhyHGVGYjSUUKNpuJUQoOIAyDvEyG8S5yfK6dhZc0Tx1KI/gviKL6qvvFs1+bWtaz58uUNnryq6kt5RzOCkPWlVqVX2a/EEBUdU1KrXLf40GoiiFXK///qpoiDXrOgqDR38JB0bw7SoL+ZB9o1RCkQjQ2CBYZKd/+VJxZRRZlqSkKiws0WFxUyCwsKiMy7hUVFhIaCrNQsKkTIsLivwKKigsj8XYlwt/WKi2N4d//uQRCSAAjURNIHpMZBGYiaQPSYyAAABLAAAAAAAACWAAAAApUF/Mg+0aohSIRobBAsMlO//Kk4soosy1JSFRYWaLC4qZBYWFRGZdwqKiwkNBVmoWFSJkWFxX4FFRQWR+LsS4W/rFRb/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////VEFHAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAU291bmRib3kuZGUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAMjAwNGh0dHA6Ly93d3cuc291bmRib3kuZGUAAAAAAAAAACU=");
    function enableSoundForNewOrders() {
    document.getElementById("play-btn").value = 'true';
    document.getElementById("stop-btn").value = 'false'

            document.getElementById("play-sound-section").style.display = "none";
    document.getElementById("stop-sound-section").style.display = "block";
    }


    function disableSoundForNewOrders() {
    document.getElementById("stop-btn").value = 'true';
    document.getElementById("play-btn").value = 'false'

            document.getElementById("play-sound-section").style.display = "block";
    document.getElementById("stop-sound-section").style.display = "none";
    }

    async function CheckPendingOrders() {
    const url = <?= "'" . Yii::$app->params['apiEndpoint'] . '/v1/order/check-for-pending-orders/' . $restaurant_model->restaurant_uuid . "'" ?>;
    fetch(url)
            .then(res => res.json())
            .then(data => {

            $("#new-order-table").load(<?= "'" . yii\helpers\Url::to(['site/check-for-new-orders', 'restaurant_uuid' => $restaurant_model->restaurant_uuid]) . "'" ?>);
            if (data && document.getElementById("play-btn").value == 'true' && document.getElementById("stop-btn").value == 'false') {
            console.log('play');
            soundForNewOrders.play();
            } else if (!data && document.getElementById("stop-btn").value == 'true' && document.getElementById("play-btn").value == 'false') {
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
                    data: <?= json_encode($sold_item_chart_data_this_week) ?>,
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
            name: 'Orders',
                    data: <?= json_encode($orders_received_chart_data_this_week) ?>,
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
                    data: <?= json_encode($revenue_generated_chart_data_this_week) ?>,
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
                    data: <?= json_encode($customer_chart_data_this_week) ?>,
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
    function addData(chart, data) {
    chart.opts.series[0].data = data;
    chart.update();
    }

    //Get customer gained
    document.getElementById("getCustomerGainedLast7DaysData").addEventListener("click", function(){
      $('#dropdownCustomerGained').html('Last 7 days');
      $('.number-of-customer-gained').html(<?= $number_of_all_customer_gained_this_week ?>);
      addData(customerGainedChart,<?= json_encode($customer_chart_data_this_week) ?>);
    });
    document.getElementById("getCustomerGainedLastMonth").addEventListener("click", function(){
      $('#dropdownCustomerGained').html('Last Month');
      $('.number-of-customer-gained').html(<?= $number_of_all_customer_gained_last_month ?>);
      addData(customerGainedChart,<?= json_encode($customer_chart_data_last_month) ?>);
    });
    document.getElementById("getCustomerGainedLast3Months").addEventListener("click", function(){
      $('#dropdownCustomerGained').html('Last 3 Months');
      $('.number-of-customer-gained').html(<?= $number_of_all_customer_gained_last_three_months ?>);
      addData(customerGainedChart,<?= json_encode($customer_chart_data_last_three_months) ?>);
    });



    // Get Revenue Generated
    document.getElementById("getRevenueGeneratedLast7DaysData").addEventListener("click", function(){
   $('#dropdownRevenueGenerated').html('Last 7 days');
   $('.number-of-revenue-generated').html (<?= number_format((float)$number_of_all_revenue_generated_this_week, 2, '.', '');  ?>);
   addData(revenueGeneratedChart,<?= json_encode($revenue_generated_chart_data_this_week) ?>);
 });
 document.getElementById("getRevenueGeneratedLastMonth").addEventListener("click", function(){
   $('#dropdownRevenueGenerated').html('Last Month');
   $('.number-of-revenue-generated').html (<?= number_format((float)$number_of_all_revenue_generated_last_month, 2, '.', '');  ?>);
   addData(revenueGeneratedChart,<?= json_encode($revenue_generated_chart_data_last_month) ?>);
 });
 document.getElementById("getRevenueGeneratedLast3Months").addEventListener("click", function(){
   $('#dropdownRevenueGenerated').html('Last 3 Months');
   $('.number-of-revenue-generated').html (<?= number_format((float)$number_of_all_revenue_generated_last_three_months, 2, '.', '');  ?>);
   addData(revenueGeneratedChart,<?= json_encode($revenue_generated_chart_data_last_three_months) ?>);
 });


    // Get Sold Items
    document.getElementById("getSoldItemsLast7DaysData").addEventListener("click", function(){
      $('#dropdownSoldItems').html('Last 7 days');
      $('.number-of-sold-items').html(<?= $number_of_all_sold_item_this_week ?>);
      addData(soldItemsChart,<?= json_encode($sold_item_chart_data_this_week) ?>);
    });
    document.getElementById("getSoldItemsLastMonth").addEventListener("click", function(){
      $('#dropdownSoldItems').html('Last Month');
      $('.number-of-sold-items').html(<?= $number_of_all_sold_item_last_month ?>);
      addData(soldItemsChart,<?= json_encode($sold_item_chart_data_last_month) ?>);
    });
    document.getElementById("getSoldItemsLast3Months").addEventListener("click", function(){
      $('#dropdownSoldItems').html('Last 3 Months');
      $('.number-of-sold-items').html(<?= $number_of_all_sold_item_last_three_months ?>);
      addData(soldItemsChart,<?= json_encode($sold_item_chart_data_last_three_months) ?>);
    });


    // Get Order Recevied
    document.getElementById("getOrderReceivedLast7DaysData").addEventListener("click", function(){
      $('#dropdownOrdersReceived').html('Last 7 days');
      $('.number-of-orders-received').html(<?= $number_of_all_orders_received_this_week ?>);
      addData(orderReceivedChart,<?= json_encode($orders_received_chart_data_this_week) ?>);
    });
    document.getElementById("getOrderReceivedLastMonth").addEventListener("click", function(){
      $('#dropdownOrdersReceived').html('Last Month');
      $('.number-of-orders-received').html(<?= $number_of_all_orders_received_last_month ?>);
      addData(orderReceivedChart,<?= json_encode($orders_received_chart_data_last_month) ?>);
    });
    document.getElementById("getOrderReceivedLast3Months").addEventListener("click", function(){
      $('#dropdownOrdersReceived').html('Last 3 Months');
      $('.number-of-orders-received').html(<?= $number_of_all_orders_received_last_three_months ?>);
      addData(orderReceivedChart,<?= json_encode($orders_received_chart_data_last_three_months) ?>);
    });

  });


</script>

<!-- Dashboard Ecommerce Starts -->
<section id="dashboard-ecommerce">
    <div class="row">
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="card">
                <div style="padding:21px">
                    <div class="avatar bg-rgba-primary p-50 m-0">
                        <div class="avatar-content">
                            <i class="feather icon-users text-primary font-medium-5"></i>
                        </div>
                    </div>
                    <div class="dropdown chart-dropdown" style="float:right">
                        <button class="btn btn-sm border-0 dropdown-toggle" style="font-size:15px; padding-right: 0px;" type="button" id="dropdownCustomerGained" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownCustomerGained">

                            <button   id="getCustomerGainedLast7DaysData" class="dropdown-item" style="width:100%">Last 7 Days</button>
                            <button   id="getCustomerGainedLastMonth" class="dropdown-item" style="width:100%">Last Month</button>
                            <button   id="getCustomerGainedLast3Months" class="dropdown-item" style="width:100%">Last 3 Months</button>

                        </div>
                    </div>

                    <h2 class="text-bold-700 mt-1 number-of-customer-gained">
                        <?= $number_of_all_customer_gained_this_week ? $number_of_all_customer_gained_this_week : 0 ?>
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
                <div style="padding:21px">
                    <div class="avatar bg-rgba-success p-50 m-0">
                        <div class="avatar-content">
                            <i class="feather icon-credit-card text-success font-medium-5"></i>
                        </div>
                    </div>
                    <div class="dropdown chart-dropdown" style="float:right">
                        <button class="btn btn-sm border-0 dropdown-toggle" style="font-size:15px;    padding-right: 0px;" type="button" id="dropdownRevenueGenerated" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownRevenueGenerated">
                            <button id="getRevenueGeneratedLast7DaysData" class="dropdown-item" style="width:100%">Last 7 Days</button>
                            <button id="getRevenueGeneratedLastMonth" class="dropdown-item" style="width:100%">Last Month</button>
                            <button id="getRevenueGeneratedLast3Months" class="dropdown-item" style="width:100%">Last 3 Months</button>

                        </div>
                    </div>
                    <div style="  margin-bottom: 0.5rem;  margin-top: 1rem !important;">
                      <h2 style="  display: contents;" class="text-bold-700 mt-1 number-of-revenue-generated">
                          <?= number_format($number_of_all_revenue_generated_this_week,3);  ?>
                      </h2>
                      <h2 style="  display: contents;">
                          KWD
                      </h2>
                    </div>
                    <p class="mb-0">Revenue Generated</p>
                </div>

                <div class="card-content">
                    <div id="revenue-generated-chart"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="card">
                <div style="padding:21px">
                    <div class="avatar bg-rgba-danger p-50 m-0">
                        <div class="avatar-content">
                            <i class="feather icon-shopping-cart text-danger font-medium-5"></i>
                        </div>
                    </div>
                    <div class="dropdown chart-dropdown" style="float:right">
                        <button class="btn btn-sm border-0 dropdown-toggle" style="font-size:15px;    padding-right: 0px;" type="button" id="dropdownSoldItems" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownSoldItems">
                            <button   id="getSoldItemsLast7DaysData" class="dropdown-item" style="width:100%">Last 7 Days</button>
                            <button   id="getSoldItemsLastMonth" class="dropdown-item" style="width:100%">Last Month</button>
                            <button   id="getSoldItemsLast3Months" class="dropdown-item" style="width:100%">Last 3 Months</button>
                        </div>

                    </div>
                    <h2 class="text-bold-700 mt-1 number-of-sold-items">
                        <?= $number_of_all_sold_item_this_week ? $number_of_all_sold_item_this_week : 0 ?>
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
                <div style="padding:21px">
                    <div class="avatar bg-rgba-warning p-50 m-0">
                        <div class="avatar-content">
                            <i class="feather icon-package text-warning font-medium-5"></i>
                        </div>
                    </div>
                    <div class="dropdown chart-dropdown" style="float:right">
                        <button class="btn btn-sm border-0 dropdown-toggle" style="font-size:15px;    padding-right: 0px;" type="button" id="dropdownOrdersReceived" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownOrdersReceived">
                            <button   id="getOrderReceivedLast7DaysData" class="dropdown-item" style="width:100%">Last 7 Days</button>
                            <button   id="getOrderReceivedLastMonth" class="dropdown-item" style="width:100%">Last Month</button>
                            <button   id="getOrderReceivedLast3Months" class="dropdown-item" style="width:100%">Last 3 Months</button>
                        </div>
                    </div>
                    <h2 class="text-bold-700 mt-1 number-of-orders-received">
                        <?= $number_of_all_orders_received_this_week ? $number_of_all_orders_received_this_week : 0 ?>
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
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="card text-center">

                <div class="card-content">


                    <div class="card-body">
                        <div class="avatar bg-rgba-primary p-50 m-0 mb-1">
                            <div class="avatar-content">
                                <i class="feather icon-users text-primary font-medium-5"></i>
                            </div>
                        </div>
                        <h2 class="text-bold-700"><?= $today_customer_gained ?></h2>
                        <p class="mb-0 line-ellipsis">Today's Customer Gained</p>


                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="card text-center">
                <div class="card-content">
                    <div class="card-body">
                        <div class="avatar bg-rgba-success p-50 m-0 mb-1">
                            <div class="avatar-content">
                                <i class="feather icon-credit-card text-success font-medium-5"></i>
                            </div>
                        </div>
                        <h2 class="text-bold-700">  <?= Yii::$app->formatter->asCurrency($today_revenue_generated, 'KWD', [NumberFormatter::MIN_FRACTION_DIGITS => 3, NumberFormatter::MAX_FRACTION_DIGITS => 5]) ?></h2>
                        <p class="mb-0 line-ellipsis">Today's Revenue Generated</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="card text-center">
                <div class="card-content">
                    <div class="card-body">
                        <div class="avatar bg-rgba-danger p-50 m-0 mb-1">
                            <div class="avatar-content">
                                <i class="feather icon-shopping-cart text-danger font-medium-5"></i>
                            </div>
                        </div>
                        <h2 class="text-bold-700"><?= $today_sold_items ?></h2>
                        <p class="mb-0 line-ellipsis">Today's Sold Items</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="card text-center">
                <div class="card-content">
                    <div class="card-body">
                        <div class="avatar bg-rgba-warning p-50 m-0 mb-1">
                            <div class="avatar-content">
                                <i class="feather icon-package text-warning font-medium-5"></i>
                            </div>
                        </div>
                        <h2 class="text-bold-700"><?= $today_orders_received ?></h2>
                        <p class="mb-0 line-ellipsis">Today's Orders Received</p>
                    </div>
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

                            <div id="play-sound-section">
                                <?=
                                Html::button(' <i class="fa fa-play" style="margin-right: 10px;"></i> Play sound for new orders', ['class' => 'btn btn-success mr-1 mb-1 waves-effect waves-light', 'id' => 'play-btn', 'value' => 'false', 'onclick' => 'enableSoundForNewOrders()']);
                                ?>
                            </div>
                            <div id="stop-sound-section" style="display: none">
                                <?=
                                Html::button('<i class="fa fa-stop" style="margin-right: 10px;"></i> Stop sound for new orders', ['class' => 'btn btn-danger mr-1 mb-1 waves-effect waves-light', 'id' => 'stop-btn', 'value' => 'false', 'onclick' => 'disableSoundForNewOrders()']);
                                ?>
                            </div>


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
                                    <tbody id="new-order-table">

                                        <?php
                                        foreach ($incoming_orders as $order) {
                                            ?>
                                            <tr>
                                                <td>
                                                    <?=
                                                    Html::a('#' . $order->order_uuid, ['order/view', 'id' => $order->order_uuid, 'restaurantUuid' => $order->restaurant_uuid], ['target' => '_blank'])
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
