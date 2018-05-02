<?php

namespace frontend\widgets;


use core\entities\Board\BoardCategory;
use yii\base\Widget;

class RegionsModalWidget extends Widget
{
    const CACHE_KEY = 'regionsModal-';

    /* @var BoardCategory|null */
    public $category;

    public function run()
    {
        return \Yii::$app->cache->getOrSet(self::CACHE_KEY . ($this->category ? $this->category->id : 0), function () {
            return $this->render('regions-modal', ['category' => $this->category]);
        }, 60);
    }
}