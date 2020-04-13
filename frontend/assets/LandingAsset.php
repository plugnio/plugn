<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class LandingAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'https://fonts.googleapis.com/css?family=Open+Sans:400,400i,700&display=swap&subset=latin-ext',
        'css/bootstrap.css',
        'css/fontawesome-all.css',
        'css/swiper.css',
        'css/magnific-popup.css',
        'css/styles.css',
    ];
    public $js = [
    'js/jquery.min.js',
    'js/popper.min.js',
    'js/bootstrap.min.js',
    'js/jquery.easing.min.js',
    'js/swiper.min.js',
    'js/jquery.magnific-popup.js',
    'js/validator.min.js',
    'js/scripts.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
