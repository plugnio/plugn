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
        'dist/css/adminlte.min.css',
        'plugins/overlayScrollbars/css/OverlayScrollbars.min.css',
        'plugins/daterangepicker/daterangepicker.css',
        'plugins/summernote/summernote-bs4.css',
        'https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700',
        'css/select2.css',
        'css/select2.min.css',
        'plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css',
    ];
    public $js = [
//        'plugins/jquery/jquery.min.js',
        'plugins/bootstrap/js/bootstrap.bundle.min.js',
        'plugins/select2/js/select2.full.min.js',
        'plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js',
        'plugins/moment/moment.min.js',
        'plugins/inputmask/min/jquery.inputmask.bundle.min.js',
        'plugins/daterangepicker/daterangepicker.js',
        'plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js',
        'plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js',
        'plugins/bootstrap-switch/js/bootstrap-switch.min.js',
        'dist/js/adminlte.min.js',
        'dist/js/demo.js',
    ];
    public $depends = [
                'yii\web\YiiAsset',

    ];

}
