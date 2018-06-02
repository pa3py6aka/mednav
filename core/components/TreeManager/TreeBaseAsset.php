<?php

namespace core\components\TreeManager;


use yii\jui\JuiAsset;
use yii\web\AssetBundle;

class TreeBaseAsset extends AssetBundle
{
    public $sourcePath = '@bower/fancytree';

    public $js = [
        'dist/jquery.fancytree-all.min.js',
    ];

    public $css = [
        'dist/skin-lion/ui.fancytree.min.css',
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        JuiAsset::class,
    ];
}