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
        'javascript/apexcharts/apexcharts.css',
        'javascript/jvectormap/jquery-jvectormap-2.0.5.css'
    ];
    
    public $js = [
        'javascript/apexcharts/apexcharts.common.js',
        'javascript/apexcharts/apexcharts.js',

        'javascript/jvectormap/jquery-jvectormap-2.0.5.min.js',
        'javascript/jvectormap/jquery-jvectormap-world-mill.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
