<div id="chart_<?= $id ?>"></div>

<?php 

if($currency_code) { 
      
  $js = "
   var tooltip_". $id ." = {
          y: {
            formatter: (val) => {
              return val + '". $currency_code ."';
            }
          },
          x: {
            show: false,
          }
        };
   ";
   
} else { 
  $show = $type == 'line' ? 'false' : 'true';

  $js = "var tooltip_". $id ." = {
    y: {
      formatter: (val) => {
        return val;
      }
    },
    x: {
      show: ". $show .",
    }
  }";
} 
    
$js .= "
 
    var chartOptions_". $id ." = {
      series: [
        {
          name: '". $title ."',
          data: ".json_encode($seriesData) .",
        },
      ],
      markers: {
        size: 0,
        colors: '".$color . "',
        strokeColors: '". $color . "',
        strokeWidth: 2,
        strokeOpacity: 0.9,
        strokeDashArray: 0,
        fillOpacity: 1,
        discrete: [],
        shape: 'circle',
        radius: 2,
        offsetX: 0,
        offsetY: 0,
        onClick: undefined,
        onDblClick: undefined,
        showNullDataPoints: true,
        hover: {
          size: 4,
          sizeOffset: 3
        }
      },
      chart: {
        height: 350,
        type: '". $type ."',
        toolbar: {
          show: false,
        },
        zoom: {
          enabled: false,
        },
      },
      dataLabels: {
        enabled: false,
      },
      stroke: {
        colors: ['". $color ."'],
        curve: 'straight',
        width: ". ($type == 'line' ? '2.5' : '2') . ",
      },
      fill: {
        colors: ['". $color ."']
      },
      plotOptions: {
        bar: {
          columnWidth: '20%',
          horizontal: ". ($type == 'line' ? 'false' : 'true') .",
        }
      },
      xaxis: {
        categories: ".json_encode($categories)."
      },
      tooltip: tooltip_". $id . "
    };
";

if($title) { 

      $js .= "chartOptions_". $id .".title = {
        text: '". $title ."',
        align: 'left',
        offsetX: 10,
      };
      ";

} 

if($subtitle) { 
      $js .= "chartOptions_". $id .".subtitle = {
        text: '". $subtitle ."',
        align: 'left'
      };";
    } 

    $js .= "
 
    $(document).ready(function() {
      window.chart_". $id ." = new ApexCharts(document.getElementById('chart_". $id ."'), chartOptions_". $id .");
      window.chart_". $id .".render();
    });  ";

$this->registerJs($js);
