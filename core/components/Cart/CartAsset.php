<?php

namespace core\components\Cart;


use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class CartAsset extends AssetBundle
{
    public $sourcePath = '@core/components/Cart/assets';
    //public $basePath = '@webroot';
    //public $baseUrl = '@web';

    public $js = [
        'main.js',
    ];

    public $depends = [
        JqueryAsset::class,
    ];
}