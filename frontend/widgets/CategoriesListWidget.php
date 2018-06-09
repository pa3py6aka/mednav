<?php

namespace frontend\widgets;


use core\entities\Board\BoardCategory;
use core\entities\Company\CompanyCategory;
use core\entities\Geo;
use core\helpers\BoardHelper;
use core\helpers\CompanyHelper;
use yii\base\Widget;
use yii\bootstrap\Html;

class CategoriesListWidget extends Widget
{
    public $component;

    /* @var BoardCategory|CompanyCategory Сюда передаём класс, например BoardCategory::class */
    public $categoryClass;

    /* @var BoardHelper|CompanyHelper Сюда передаём класс, например BoardHelper::class */
    public $helperClass;

    /* @var BoardCategory|CompanyCategory|null */
    public $category;

    /* @var Geo|null */
    public $region;

    private $isMainPage;
    private $cacheKeyPrefix;

    public function init()
    {
        parent::init();
        if (!$this->component) {
            throw new \InvalidArgumentException("Не определён компонент.");
        }
        if (!$this->categoryClass) {
            throw new \InvalidArgumentException("Не определён класс категории.");
        }

        $this->isMainPage = !$this->category;
        $this->cacheKeyPrefix = 'catList-' . $this->component . '-';
    }

    public function run()
    {
        $cacheKey = $this->cacheKeyPrefix . ($this->category ? $this->category->id : 0) . '-' . ($this->region ? $this->region->id : 0);
        return \Yii::$app->cache->getOrSet($cacheKey, function () {
            if ($this->category) {
                $categories = $this->category->getChildren()->active()->all();
            } else {
                $query = $this->categoryClass::find()->active()->roots();
                $query->andWhere(['not_show_on_main' => 0]);
                $categories = $query->all();
            }

            return $this->render('categories-list', [
                'categories' => $categories,
                'widget' => $this,
            ]);
        }, 60);
    }

    /**
     * @param BoardCategory|CompanyCategory $category
     * @return string
     */
    public function generateList($category)
    {
        $html = '';

        // Проверка параметра "Выводить дочерние разделы только в родителе"
        if ($category->children_only_parent && (!$this->category || $this->category->id !== $category->id)) {
            return '';
        }

        $children = $this->isMainPage
            ? $category->getChildren()->andWhere(['not_show_on_main' => 0])->active()->all()
            : $category->children;
        if ($children) {
            $html = '<ul class="list-section-listing">';
            foreach ($children as $child) {
                $html .= '<li>';
                $html .= Html::a($child->name, $this->helperClass::categoryUrl($child, $this->region, false, false));
                $html .= ' <sup class="list-section-count">' . $this->helperClass::getCountInCategory($child) . '</sup></li>';
                $html .= '</li>';
                $html .= $this->generateList($child);
            }
            $html .= '</ul>';
        }
        return $html;
    }
}