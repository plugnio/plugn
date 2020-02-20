<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class DashboardAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'plugins/datatables-bs4/css/dataTables.bootstrap4.css',
        'plugins/fontawesome-free/css/all.min.css',
        'https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css',
        'plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css',
        'plugins/icheck-bootstrap/icheck-bootstrap.min.css',
        'plugins/jqvmap/jqvmap.min.css',
        'dist/css/adminlte.min.css',
        'plugins/overlayScrollbars/css/OverlayScrollbars.min.css',
        'plugins/daterangepicker/daterangepicker.css',
        'plugins/summernote/summernote-bs4.css',
        'https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700',

    ];
    public $js = [
//        'dist/js/pages/dashboard.js',
//        'plugins/jquery/jquery.min.js',
        'plugins/moment/moment.min.js',
        'plugins/daterangepicker/daterangepicker.js',
        'plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js',
        'plugins/summernote/summernote-bs4.min.js',
        'plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js',
        'dist/js/adminlte.js',
        'dist/js/demo.js',
        'plugins/jquery-ui/jquery-ui.min.js',
        'plugins/bootstrap/js/bootstrap.bundle.min.js',
        'plugins/chart.js/Chart.min.js',
//        'plugins/sparklines/sparkline.js',
//        'plugins/jqvmap/jquery.vmap.min.js',
//        'plugins/jqvmap/maps/jquery.vmap.usa.js',
        'plugins/jquery-knob/jquery.knob.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];

}
