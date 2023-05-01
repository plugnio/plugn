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
    ];
    
    public $js = [
        'javascript/apexcharts/apexcharts.common.js',
        'javascript/apexcharts/apexcharts.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
