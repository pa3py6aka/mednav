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

    private $isMainPage;

    public function init()
    {
        parent::init();
        $this->isMainPage = !$this->category;
    }

    public function run()
    {
        $cacheKey = self::CACHE_KEY_PREFIX . ($this->category ? $this->category->id : 0) . '-' . ($this->region ? $this->region->id : 0);
        return \Yii::$app->cache->getOrSet($cacheKey, function () {
            if ($this->category) {
                $categories = $this->category->getChildren()->active()->all();
            } else {
                $query = BoardCategory::find()->active()->roots();
                //if (!$this->region) {
                    $query->andWhere(['not_show_on_main' => 0]);
                //}
                $categories = $query->all();
            }

            return $this->render('board-categories-list', [
                'categories' => $categories,
                'widget' => $this,
            ]);
        }, 60);
    }

    public function generateList(BoardCategory $category)
    {
        $html = '';

        // Проверка параметра "Выводить дочерние разделы только в родителе"
        if ($category->children_only_parent && (!$this->category || $this->category->id !== $category->id)) {
            return '';
        }

        $children = $this->isMainPage
            ? $category->getChildren()->andWhere(['not_show_on_main' => 0])->all()
            : $category->children;
        if ($children) {
            $html = '<ul class="list-section-listing">';
            foreach ($children as $child) {
                $html .= '<li>';
                $html .= Html::a($child->name, BoardHelper::categoryUrl($child, $this->region, false, false));
                $html .= ' <sup class="list-section-count">' . BoardHelper::getCountInCategory($child) . '</sup></li>';
                $html .= '</li>';
                $html .= $this->generateList($child);
            }
            $html .= '</ul>';
        }
        return $html;
    }
}