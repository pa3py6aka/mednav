<?php

namespace backend\assets;

use yii\web\AssetBundle;


class RegionsAttachAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [];

    public $js = [
        'js/regions-attach.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
