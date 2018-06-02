<?php

namespace core\components\TreeManager;


use yii\web\AssetBundle;

class TreeAsset extends AssetBundle
{
    public $sourcePath = '@core/components/TreeManager';

    public $css = [
        'styles.css',
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        TreeBaseAsset::class,
    ];
}