<?php

namespace core\components\ContentBlocks;


use core\entities\Article\ArticleCategory;
use core\entities\Board\BoardCategory;
use core\entities\Brand\BrandCategory;
use core\entities\CNews\CNewsCategory;
use core\entities\Company\CompanyCategory;
use core\entities\ContentBlock;
use core\entities\Expo\ExpoCategory;
use core\entities\News\NewsCategory;
use core\entities\Trade\TradeCategory;
use core\forms\manage\CategoryForm;
use yii\base\Widget;

class ContentBlocksWidget extends Widget
{
    public function run()
    {
        $html = [];
        foreach (ContentBlock::modulesArray() as $module => $name) {
            $html[] = $this->render('module-box', [
                'module' => $module,
                'name' => $name,
                'places' => self::getPlacesFor($module),
                'widget' => $this,
            ]);
        }

        return implode("\n", $html);
    }

    public static function getPlacesFor($module): array
    {
        $places = [
            ContentBlock::PLACE_SIDEBAR_LEFT => 'Sidebar left',
            ContentBlock::PLACE_MAIN => 'Main content',
            ContentBlock::PLACE_SIDEBAR_RIGHT => 'Sidebar right',
        ];

        if ($module != ContentBlock::MODULE_MAIN_PAGE) {
            unset($places[ContentBlock::PLACE_SIDEBAR_LEFT]);
        }

        return $places;
    }

    /**
     * @param int $module
     * @param int $place
     * @param int $page
     * @return array|ContentBlock[]
     */
    public function getBlocksFor($module, $place, $page): array
    {
        return ContentBlock::find()
            ->where(['module' => $module, 'place' => $place, 'page' => $page])
            ->orderBy(['sort' => SORT_ASC])
            ->all();
    }

    public static function getCategoriesFor($module): array
    {
        if ($module == ContentBlock::MODULE_TRADE) {
            $class = TradeCategory::class;
        } else if ($module == ContentBlock::MODULE_BOARD) {
            $class = BoardCategory::class;
        } else if ($module == ContentBlock::MODULE_COMPANY) {
            $class = CompanyCategory::class;
        } else if ($module == ContentBlock::MODULE_ARTICLE) {
            $class = ArticleCategory::class;
        } else if ($module == ContentBlock::MODULE_BRAND) {
            $class = BrandCategory::class;
        } else if ($module == ContentBlock::MODULE_CNEWS) {
            $class = CNewsCategory::class;
        } else if ($module == ContentBlock::MODULE_EXPO) {
            $class = ExpoCategory::class;
        } else if ($module == ContentBlock::MODULE_NEWS) {
            $class = NewsCategory::class;
        } else {
            $class = BoardCategory::class;
        }

        $categories = CategoryForm::parentCategoriesList($class, false, false);
        return [0 => 'Главная страница'] + $categories;
    }
}