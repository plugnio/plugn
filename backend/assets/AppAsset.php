<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    
    public $css = [
        'css/site.css',
        'app-assets/apexcharts/apexcharts.css',
        'app-assets/jvectormap/jquery-jvectormap-2.0.5.css'
    ];
    
    public $js = [
        'app-assets/apexcharts/apexcharts.common.js',
        'app-assets/apexcharts/apexcharts.js',
        'app-assets/jvectormap/jquery-jvectormap-2.0.5.min.js',
        'app-assets/jvectormap/jquery-jvectormap-world-mill.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
