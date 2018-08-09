<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class MultiSelectAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/multiselect.css',
    ];

    public $js = [
        'js/multiselect.js',
    ];

    public $depends = [
        AppAsset::class,
    ];
}
