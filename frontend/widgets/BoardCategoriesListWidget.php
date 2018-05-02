<?php

namespace frontend\widgets;


use core\entities\Board\BoardCategory;
use core\entities\Geo;
use core\helpers\BoardHelper;
use yii\base\Widget;
use yii\bootstrap\Html;

class BoardCategoriesListWidget extends Widget
{
    public const CACHE_KEY_PREFIX = 'boardCatsList-';

    /* @var BoardCategory|null */
    public $category;

    /* @var Geo|null */
    public $region;

    public function run()
    {
        $cacheKey = self::CACHE_KEY_PREFIX . ($this->category ? $this->category->id : 0) . '-' . ($this->region ? $this->region->id : 0);
        return \Yii::$app->cache->getOrSet($cacheKey, function () {
            if ($this->category) {
                $categories = $this->category->getDescendants()->all();
            } else {
                $categories = BoardCategory::find()->roots()->all();
            }

            return $this->render('board-categories-list', ['categories' => $categories, 'geo' => $this->region]);
        }, 60);
    }

    public static function generateList(BoardCategory $category, $region)
    {
        $html = '';
        if ($children = $category->children) {
            $html = '<ul class="list-section-listing">';
            foreach ($children as $child) {
                $html .= '<li>';
                $html .= Html::a($child->name, BoardHelper::categoryUrl($child, $region));
                $html .= ' <sup class="list-section-count">' . BoardHelper::getCountInCategory($child) . '</sup></li>';
                $html .= '</li>';
                $html .= self::generateList($child, $region);
            }
            $html .= '</ul>';
        }
        return $html;
    }
}