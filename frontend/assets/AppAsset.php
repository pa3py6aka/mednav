<?php

namespace frontend\assets;

use yii\bootstrap\BootstrapAsset;
use yii\bootstrap\BootstrapPluginAsset;
use yii\web\AssetBundle;
use yii\web\YiiAsset;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        //'css/font-awesome.min.css',
        'https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css',
        'css/owl.carousel.min.css',
        'css/owl.theme.default.min.css',
        'lib/fb/source/jquery.fancybox.css?v=2.1.5',
        'css/styles.css',
    ];
    public $js = [
        'js/owl.carousel.js',
        'js/button-up.js',
        'lib/fb/lib/jquery.mousewheel.pack.js?v=3.1.3',
        'lib/fb/source/jquery.fancybox.pack.js?v=2.1.5',
        'js/main.js',
    ];
    public $depends = [
        YiiAsset::class,
        BootstrapAsset::class,
        BootstrapPluginAsset::class,
    ];
}
