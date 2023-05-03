<?php
use yii\helpers\Html;
use yii\helpers\Url;
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
 
 function setChartData(chart, id, data, categories) {
    chart.updateSeries([{
                            name: id,
                            data: data
                        }]);    
                         
                       chart_fees.updateOptions ({
                            xaxis: {
                                categories: categories
                            }
                       });        
  }
  
$(function () {
 
  $('select[name=\"interval-fees\"]').change(function(event) {
    
    $.ajax({
				url: '". Url::to(['stats/graph-fees']) ."',
				method: 'POST', 
				data: {
				    interval: event.target.value
				},
				dataType: 'json',
				beforeSend: function() {
					$('input[name=\"interval-fees\"]').prop('disabled', true);
				},
				complete: function() {
					$('input[name=\"interval-fees\"]').prop('disabled', false);
				},
				success: function(json) {
				 	 setChartData(chart_fees, 'fees', json.seriesData, json.categories);		                
				}
			});
  });
  
  $('select[name=\"interval-stores\"]').change(function(event) {
    
    $.ajax({
				url: '". Url::to(['stats/graph-stores']) ."',
				method: 'POST', 
				data: {
				    interval: event.target.value
				},
				dataType: 'json',
				beforeSend: function() {
					$('input[name=\"interval-stores\"]').prop('disabled', true);
				},
				complete: function() {
					$('input[name=\"interval-stores\"]').prop('disabled', false);
				},
				success: function(json) {
				 	 setChartData(chart_stores, 'stores', json.seriesData, json.categories);		                
				}
			});
  });
  
  $('select[name=\"interval-customers\"]').change(function(event) {
    
    $.ajax({
				url: '". Url::to(['stats/graph-customers']) ."',
				method: 'POST', 
				data: {
				    interval: event.target.value
				},
				dataType: 'json',
				beforeSend: function() {
					$('input[name=\"interval-customers\"]').prop('disabled', true);
				},
				complete: function() {
					$('input[name=\"interval-customers\"]').prop('disabled', false);
				},
				success: function(json) {
				 	 setChartData(chart_customers, 'customers', json.seriesData, json.categories);		                
				}
			});
  });
  
  $('select[name=\"interval-orders\"]').change(function(event) {
    
    $.ajax({
				url: '". Url::to(['stats/graph-orders']) ."',
				method: 'POST', 
				data: {
				    interval: event.target.value
				},
				dataType: 'json',
				beforeSend: function() {
					$('input[name=\"interval-orders\"]').prop('disabled', true);
				},
				complete: function() {
					$('input[name=\"interval-orders\"]').prop('disabled', false);
				},
				success: function(json) {
				 	 setChartData(chart_orders, 'orders', json.seriesData, json.categories);		                
				}
			});
  });
  
  
  
});

";
$this->registerJs($js);

?>
<div class="site-index">
    <div class="body-content">

        <div class="panel panel-default">
            <div class="panel-heading">Stores by country</div>

            <div class="panel-body">

                <div class="hidden">
                    <?= Html::beginForm(['/stats/graph'], 'POST', ['class' => "form-inline form-filter-map"]); ?>

                    <div class="form-group mb-2">
                        <label for="date_start">Start Date</label>
                        <?= Html::input('date', 'date_start', null, ["id" => "date_start", "class"=>"form-control"]); ?>
                    </div>

                    <div class="form-group mb-2">
                        <label for="end_start">End Date</label>
                        <?= Html::input('date', 'date_end', null, ["id" => "date_end", "class"=>"form-control"]); ?>
                    </div>

                    <div class="form-group" style="background: #f4f6f9;  margin-bottom: 0px; padding-bottom: 0px; background:#f4f6f9 ">
                        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary mb-2']) ?>
                    </div>
                    <?= Html::endForm(); ?></div>

                <div id="world-map-gdp"></div>
            </div>
        </div>


        <div class="grid">

            <div class="row">

                <?php
                /*
                <div class="col-12 col-lg-6">
                    <div class="panel panel-default" id="panel-fees">
                        <div class="panel-heading">

                            <span class="title">Plugn fees</span>

                            <div class="pull-right">
                                <?= Html::dropDownList('interval-fees', null, [
                                    "week" => "Week",
                                    "last-2-months" => "Last 2 month",
                                    "last-3-months" => "Last 3 month",
                                    "last-5-months" => "Last 5 month",
                                    "last-12-months" => "Last 12 month"
                                ], ["id" => "interval", "class"=>"form-control"]); ?>
                            </div>

                            <div class="clearfix"></div>
                        </div>

                        <div class="panel-body">
                            <?=  ChartWidget::widget([
                                'id' => "fees" ,
                                'color' => "red",
                                'chartdata' => $plugn_fee_chart_data,
                                'type' => "line",
                                'title'=> "",
                                'currency_code'=> $currency_code
                            ]); ?>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span class="title">Revenue Generated</span>
                            <div class="pull-right">
                                <?= Html::dropDownList('interval-revenue', null, [
                                    "week" => "Week",
                                    "last-2-months" => "Last 2 month",
                                    "last-3-months" => "Last 3 month",
                                    "last-5-months" => "Last 5 month",
                                    "last-12-months" => "Last 12 month"
                                ], ["id" => "interval", "class"=>"form-control"]); ?>
                            </div>

                            <div class="clearfix"></div>
                        </div>

                        <div class="panel-body">
                            <?=  ChartWidget::widget([
                                'id' => "revenue" ,
                                'color' => "red",
                                'chartdata' => $revenue_generated_chart_data,
                                'type' => "line",
                                'title'=> "",
                                'currency_code'=> $currency_code
                            ]); ?>
                        </div>
                    </div>

                </div>
                   */ ?>

                <div class="col-12 col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">

                            <span class="title">New stores</span>

                            <div class="pull-right">
                                <?= Html::dropDownList('interval-stores', null, [
                                    "week" => "Week",
                                    "last-2-months" => "Last 2 month",
                                    "last-3-months" => "Last 3 month",
                                    "last-5-months" => "Last 5 month",
                                    "last-12-months" => "Last 12 month"
                                ], ["id" => "interval", "class"=>"form-control"]); ?>
                            </div>

                            <div class="clearfix"></div>
                        </div>

                        <div class="panel-body">
                            <?=  ChartWidget::widget([
                                'id' => "store" ,
                                'color' => "red",
                                'chartdata' => $store_created_chart_data,
                                'type' => "line",
                                'title'=> "",
                                'currency_code'=> $currency_code
                            ]); ?>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">

                            <span class="title">Customers Gained</span>

                            <div class="pull-right">
                                <?= Html::dropDownList('interval-customers', null, [
                                    "week" => "Week",
                                    "last-2-months" => "Last 2 month",
                                    "last-3-months" => "Last 3 month",
                                    "last-5-months" => "Last 5 month",
                                    "last-12-months" => "Last 12 month"
                                ], ["id" => "interval", "class"=>"form-control"]); ?>
                            </div>

                            <div class="clearfix"></div>
                        </div>

                        <div class="panel-body">
                            <?=  ChartWidget::widget([
                                'id' => "customer" ,
                                'color' => "blue",
                                'chartdata' => $customer_chart_data,
                                'type' => "line",
                                'title'=> "",
                                'currency_code'=> $currency_code
                            ]); ?>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">

                            <span class="title">Orders Received</span>

                            <div class="pull-right">
                                <?= Html::dropDownList('interval-orders', null, [
                                    "week" => "Week",
                                    "last-2-months" => "Last 2 month",
                                    "last-3-months" => "Last 3 month",
                                    "last-5-months" => "Last 5 month",
                                    "last-12-months" => "Last 12 month"
                                ], ["id" => "interval", "class"=>"form-control"]); ?>
                            </div>

                            <div class="clearfix"></div>
                        </div>

                        <div class="panel-body">
                            <?=  ChartWidget::widget([
                                'id' => "order" ,
                                'color' => "green",
                                'chartdata' => $orders_received_chart_data,
                                'type' => "line",
                                'title'=> "",
                                'currency_code'=> $currency_code
                            ]); ?>
                        </div>
                    </div>
                </div>

            </div><!-- END .row -->
        </div>

    </div>

</div>

<style type="text/css">
    .panel-heading .title {
        font-size: 16px;
        padding-top: 8px;
        display: inline-block;
    }
</style>