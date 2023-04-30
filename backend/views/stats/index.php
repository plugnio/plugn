<?php
use yii\helpers\Html;
use backend\components\ChartWidget;

$this->title = 'Statistics';
?>

<div class="site-index">
 	<div class="body-content">

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
  </div><!-- END .row -->

</div>            	