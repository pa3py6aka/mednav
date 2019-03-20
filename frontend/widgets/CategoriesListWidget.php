<?php

namespace frontend\widgets;


use core\components\Settings;
use core\entities\Article\ArticleCategory;
use core\entities\Board\BoardCategory;
use core\entities\Brand\BrandCategory;
use core\entities\CategoryInterface;
use core\entities\Company\CompanyCategory;
use core\entities\Geo;
use core\entities\News\NewsCategory;
use core\helpers\ArticleHelper;
use core\helpers\BoardHelper;
use core\helpers\BrandHelper;
use core\helpers\CompanyHelper;
use core\helpers\NewsHelper;
use core\helpers\TradeHelper;
use Yii;
use yii\base\Widget;
use yii\bootstrap\Html;

class CategoriesListWidget extends Widget
{
    public $component;

    /* @var BoardCategory|CompanyCategory|ArticleCategory|NewsCategory|BrandCategory Сюда передаём класс, например BoardCategory::class */
    public $categoryClass;

    /* @var BoardHelper|CompanyHelper|ArticleHelper|NewsHelper|BrandHelper|TradeHelper Сюда передаём класс, например BoardHelper::class */
    public $helperClass;

    /* @var BoardCategory|CompanyCategory|ArticleCategory|NewsCategory|BrandCategory|null */
    public $category;

    /* @var Geo|null */
    public $region;

    private $isMainPage;
    private $cacheKeyPrefix;

    public function init()
    {
        parent::init();
        if (!$this->component) {
            throw new \InvalidArgumentException('Не определён компонент.');
        }
        if (!$this->categoryClass) {
            throw new \InvalidArgumentException('Не определён класс категории.');
        }

        $this->isMainPage = !$this->category;
        $this->cacheKeyPrefix = 'catList-' . $this->component . '-';
    }

    public function run()
    {
        $cacheKey = $this->cacheKeyPrefix . ($this->category ? $this->category->id : 0) . '-' . ($this->region ? $this->region->id : 0);
        return Yii::$app->cache->getOrSet($cacheKey, function () {
            if ($this->category) {
                $categories = $this->category
                    ->getChildren()
                    ->active()
                    ->orderBy(['lft' => SORT_ASC])
                    ->all();
            } else {
                $query = $this->categoryClass::find()->active()->roots();
                $query->andWhere(['not_show_on_main' => 0]);
                $query->orderBy(['lft' => SORT_ASC]);
                $categories = $query->all();
            }

            return $this->render('categories-list', [
                'categories' => $categories,
                'widget' => $this,
            ]);
        }, 60);
    }

    /**
     * @param CategoryInterface[] $categories
     * @return string
     */
    public function generateList($categories): string
    {
        $html = [];
        foreach ($categories as $n => $category) {
            if (\in_array($this->component, ['board', 'trade', 'company'])) {
                $totalCounts = $this->helperClass::getCountInCategory($category, $this->region ? $this->region->id : null);
            } else {
                $totalCounts = $this->helperClass::getCountInCategory($category);
            }
            $html[$n] = '<div class="col-md-4 col-sm-12 col-xs-12">';
            $html[$n] .= '<div class="list-section-parent">';
            $html[$n] .= '<span>&ndash;</span> <a href="' . $this->helperClass::categoryUrl($category, $this->region, false, false) . '">'. $category->name .'</a>';
            $html[$n] .= '<sup class="list-section-count">'. $totalCounts . '</sup>';
            $html[$n] .= '</div>';

            // Рендеринг субразделов
            $result = $this->getChildrenList($category);
            if ((int)($result['count'] + $totalCounts) > 0 || $this->showAll()) {
                $html[$n] .= $result['html'];
                $html[$n] .= '</div>';
            } else {
                unset($html[$n]);
            }
        }

        return implode("\n\r", $html);
    }

    private function getChildrenList(CategoryInterface $category): array
    {
        $html = '';
        $count = $totalCount = 0;

        // Проверка параметра "Выводить дочерние разделы только в родителе"
        if ($category->children_only_parent && (!$this->category || $this->category->id !== $category->id)) {
            return ['html' => $html, 'count' => $count];
        }

        $children = $this->isMainPage
            ? $category->getChildren()->andWhere(['not_show_on_main' => 0])->active()->all()
            : $category->children;
        if ($children) {
            $html .= '<ul class="list-section-listing">';
            foreach ($children as $child) {
                if (\in_array($this->component, ['board', 'trade', 'company'])) {
                    $count = $this->helperClass::getCountInCategory($child, $this->region ? $this->region->id : null);
                } else {
                    $count = $this->helperClass::getCountInCategory($child);
                }
                $result = $this->getChildrenList($child);
                $totalCount = $count + $result['count'];
                if ($totalCount > 0 || $this->showAll()) {
                    $html .= '<li>';
                    $html .= Html::a($child->name, $this->helperClass::categoryUrl($child, $this->region, false, false));
                    $html .= ' <sup class="list-section-count">' . $count . '</sup></li>';
                    $html .= '</li>';
                    $html .= $result['html'];
                }
            }
            $html .= '</ul>';
            if (!$totalCount && !$this->showAll()) {
                return ['html' => $html, 'count' => 0];
            }
        }
        return ['html' => $html, 'count' => $count];
    }

    private function showAll(): bool
    {
        return ($this->component === 'trade' && !Yii::$app->settings->get(Settings::TRADE_SHOW_EMPTY_CATEGORIES))
            || ($this->component === 'board' && !Yii::$app->settings->get(Settings::BOARD_SHOW_EMPTY_CATEGORIES));
    }
}