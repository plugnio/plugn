<?php
use yii\helpers\Html;
use backend\components\ChartWidget;

$this->title = 'Statistics';

$js = " 
    var storeByCountry = ".json_encode($storeByCountry).";
    
    $(document).ready(function() {
        $('#world-map-gdp').vectorMap({
            map: 'world_mill',
            series: {
             regions: [{
                values: storeByCountry,
                scale: ['#C8EEFF', '#0071A4'],
                normalizeFunction: 'polynomial'
             }]
            },
            onRegionTipShow: function(e, el, code) {
                el.html(el.html()+' ('+storeByCountry[code]+' stores)');
            }
        }); 
    });
";

$this->registerJs($js);
?>

<div class="site-index">
 	<div class="body-content">

        <div id="world-map-gdp"></div>

        <div class="row">

            <!--Our profit margin and payment gateway margin separated from that revenue -->

                    <div class="col-xl-3 col-lg-4">
                        <div class="card card-stats mb-4 mb-xl-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Total Orders Received</h5>
                                        <span class="h2 font-weight-bold mb-0"><?= $totalOrders ?></span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                            <i class="fas fa-chart-bar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4">
                        <div class="card card-stats mb-4 mb-xl-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Total revenue generated from that revenue</h5>
                                        <span class="h2 font-weight-bold mb-0"><?= $totalRevenue ?></span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                            <i class="fa fa-chart-pie"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4">
                        <div class="card card-stats mb-4 mb-xl-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Number of premium stores</h5>
                                        <span class="h2 font-weight-bold mb-0"><?= $totalPremium ?></span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                                            <i class="fas fa-users"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4">
                        <div class="card card-stats mb-4 mb-xl-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Number of free stores</h5>
                                        <span class="h2 font-weight-bold mb-0"><?= $totalFreeStores ?></span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                            <i class="fas fa-percent"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- END .col-xl-3 -->
            <div class="col-xl-3 col-lg-4">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">Stores with payment method usage</h5>
                                <span class="h2 font-weight-bold mb-0"><?= $totalStoresWithPaymentGateway ?></span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                    <i class="fas fa-percent"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- END .col-xl-3 -->

            <div class="col-xl-3 col-lg-4">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">Free plugn domain</h5>
                                <span class="h2 font-weight-bold mb-0"><?= $totalPlugnDomain ?></span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                    <i class="fas fa-percent"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- END .col-xl-3 -->

            <div class="col-xl-3 col-lg-4">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">Custom domain</h5>
                                <span class="h2 font-weight-bold mb-0"><?= $totalCustomDomain ?></span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                    <i class="fas fa-percent"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- END .col-xl-3 -->

                </div>

        <hr />

        <div class="row">
          <div class="col-12 col-lg-4">
          	<?=  ChartWidget::widget([
                  'id' => "revenue" ,
                  'color' => "red",
                  'chartdata' => $revenue_generated_chart_data,
                  'type' => "line",
                  'title'=> "Revenue Generated",
                  'currency_code'=> $currency_code
            ]); ?>
          </div>

          <div class="col-12 col-lg-4">
            <?=  ChartWidget::widget([
                  'id' => "customer" ,
                  'color' => "blue",
                  'chartdata' => $customer_chart_data,
                  'type' => "line",
                  'title'=> "Customers Gained",
                  'currency_code'=> $currency_code
            ]); ?>
          </div>
          
          <div class="col-12 col-lg-4">
            <?=  ChartWidget::widget([
                  'id' => "order" ,
                  'color' => "green",
                  'chartdata' => $orders_received_chart_data,
                  'type' => "line",
                  'title'=> "Orders Received",
                  'currency_code'=> $currency_code
            ]); ?>
          </div>

      </div><!-- END .row -->

    </div>

</div>            	