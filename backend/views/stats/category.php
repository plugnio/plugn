<?php

$js = "
    var options = {
        series: ". json_encode($seriesData).",
        chart: {
            width: 580,
            type: 'pie',
        },
        labels: ". json_encode($categories).",
        
    };

    var chart = new ApexCharts(document.querySelector('#category-chart'), options);
    chart.render();
    
    var orderChartOptions = {
        series: ". json_encode($orderSeriesData).",
        chart: {
            width: 580,
            type: 'pie',
        },
        labels: ". json_encode($categories).",
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };

    var chart = new ApexCharts(document.querySelector('#order-category-chart'), orderChartOptions);
    chart.render();
     
";
$this->registerJs($js);

?>

<div class="site-index">
    <div class="body-content">

        <div class="grid">
            <div class="col-12 col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-heading">

                        <span class="title">Stores by category</span>
                    </div>

                    <div class="panel-body">
                        <div id="category-chart"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid">
            <div class="col-12 col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-heading">

                        <span class="title">Orders by category</span>
                    </div>

                    <div class="panel-body">
                        <div id="order-category-chart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
