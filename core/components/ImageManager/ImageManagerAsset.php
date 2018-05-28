<?php

namespace core\components\ImageManager;

use yii\web\AssetBundle;


class ImageManagerAsset extends AssetBundle
{
    public $sourcePath = '@core/components/ImageManager';

    public $css = [];

    public $js = [
        'image-manager.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
