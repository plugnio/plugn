<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
 class AppAsset extends AssetBundle
 {
     public $basePath = '@webroot';
     public $baseUrl = '@web';
     public $css = [
       'assets/plugins/font-awesome/css/font-awesome.css',
       'assets/plugins/pace/pace-theme-flash.css',
       'assets/plugins/jquery-metrojs/MetroJs.css',
       'assets/plugins/bootstrap-select2/select2.css',
       'assets/plugins/jquery-datatable/css/jquery.dataTables.css',
       'assets/plugins/datatables-responsive/css/datatables.responsive.css',
       'css/demo.css',
       'css/component.css',
       'css/owl.carousel.css',
       'css/owl.theme.css',
       'css/pace-theme-flash.css',
       'css/jquery.sidr.light.css',
       'css/rickshaw.css',
       'assets/plugins/Mapplic/mapplic/mapplic.css',
       'css/pace-theme-flash.css',
       'assets/plugins/bootstrapv3/css/bootstrap.min.css',
       'assets/plugins/bootstrapv3/css/bootstrap-theme.min.css',
       'https://fonts.googleapis.com/icon?family=Material+Icons',
       'assets/plugins/animate.min.css',
       'assets/plugins/jquery-scrollbar/jquery.scrollbar.css',
       'css/webarch.css',
     ];
     public $js = [
       'http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js',
       'assets/plugins/jquery-morris-chart/js/morris.min.js',
       'assets/plugins/jquery-easy-pie-chart/js/jquery.easypiechart.min.js',
       'assets/plugins/jquery-flot/jquery.flot.animator.min.js',
       'assets/js/charts.js',
       'assets/plugins/pace/pace.min.js',
       'assets/plugins/jquery/jquery-1.11.3.min.js',
       'assets/plugins/bootstrapv3/js/bootstrap.min.js',
       'assets/plugins/jquery-block-ui/jqueryblockui.min.js',
       'assets/plugins/jquery-unveil/jquery.unveil.min.js',
       'assets/plugins/jquery-scrollbar/jquery.scrollbar.min.js',
       'assets/plugins/jquery-numberAnimate/jquery.animateNumbers.js',
       'assets/plugins/jquery-validation/js/jquery.validate.min.js',
       'assets/plugins/bootstrap-select2/select2.min.js',
       'js/webarch.js',
       'assets/js/chat.js',
       'assets/plugins/jquery-ui/jquery-ui-1.10.1.custom.min.js',
       'assets/plugins/jquery-ricksaw-chart/js/raphael-min.js',
       'assets/plugins/jquery-ricksaw-chart/js/d3.v2.js',
       'assets/plugins/jquery-ricksaw-chart/js/rickshaw.min.js',
       'assets/plugins/jquery-sparkline/jquery-sparkline.js',
       'assets/plugins/skycons/skycons.js',
       'assets/plugins/owl-carousel/owl.carousel.min.js',
       'http://maps.google.com/maps/api/js?sensor=true',
       'assets/plugins/jquery-gmap/gmaps.js',
       'assets/plugins/Mapplic/js/jquery.easing.js',
       'assets/plugins/Mapplic/js/jquery.mousewheel.js',
       'assets/plugins/Mapplic/js/hammer.js',
       'assets/plugins/Mapplic/mapplic/mapplic.js',
       'assets/plugins/jquery-flot/jquery.flot.js',
       'assets/plugins/jquery-metrojs/MetroJs.min.js',
     ];
     public $depends = [
         'yii\web\YiiAsset',
         'yii\bootstrap\BootstrapAsset',
     ];
 }
