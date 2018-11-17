<?php

namespace core\helpers;


use core\components\Settings;
use core\entities\Board\BoardCategoryRegion;
use core\entities\CategoryInterface;
use core\entities\Company\CompanyCategoryRegion;
use core\entities\Trade\TradeCategoryRegion;
use Yii;
use yii\web\View;

class CategoryHelper
{
    public static function categoryParentsString(CategoryInterface $category): string
    {
        $items = [];
        foreach ($category->parents as $parent) {
            if ($parent->isRoot()) {
                continue;
            }
            $items[] = $parent->name;
        }
        $items[] = $category->name;
        return implode(' / ', $items);
    }

    /**
     * @param string $module
     * @param View $view
     * @param string $defaultTitle
     * @param CategoryInterface|null $category
     * @param BoardCategoryRegion|TradeCategoryRegion|CompanyCategoryRegion|null $categoryRegion
     */
    public static function registerHeadMeta($module, View $view, $defaultTitle, CategoryInterface $category = null, $categoryRegion = null): void
    {
        $page = Yii::$app->request->get('page');
        $title = null;
        $description = null;
        $keywords = null;
        if ($categoryRegion) {
            $title = $categoryRegion->meta_title;
            $description = $categoryRegion->meta_description;
            $keywords = $categoryRegion->meta_keywords;
        }
        if ($category) {
            if ($page && !$categoryRegion) {
                $title = $category->meta_title_other;
                $description = $category->meta_description_other;
                $keywords = $category->meta_keywords_other;
            } else {
                $title = $title ?: ($category->meta_title ?: ($category->title ?: $category->name));
                $description = $description ?: $category->meta_description;
                $keywords = $keywords ?: $category->meta_keywords;
            }
        }

        if (!$category && !$categoryRegion) {
            switch ($module) {
                case 'company':
                    $settingsTitle = Settings::COMPANY_META_TITLE;
                    $settingsDescription = Settings::COMPANY_META_DESCRIPTION;
                    $settingsKeywords = Settings::COMPANY_META_KEYWORDS;
                    break;
                case 'trade':
                    $settingsTitle = Settings::TRADE_META_TITLE;
                    $settingsDescription = Settings::TRADE_META_DESCRIPTION;
                    $settingsKeywords = Settings::TRADE_META_KEYWORDS;
                    break;
                case 'article':
                    $settingsTitle = Settings::ARTICLE_META_TITLE;
                    $settingsDescription = Settings::ARTICLE_META_DESCRIPTION;
                    $settingsKeywords = Settings::ARTICLE_META_KEYWORDS;
                    break;
                case 'news':
                    $settingsTitle = Settings::NEWS_META_TITLE;
                    $settingsDescription = Settings::NEWS_META_DESCRIPTION;
                    $settingsKeywords = Settings::NEWS_META_KEYWORDS;
                    break;
                case 'cnews':
                    $settingsTitle = Settings::CNEWS_META_TITLE;
                    $settingsDescription = Settings::CNEWS_META_DESCRIPTION;
                    $settingsKeywords = Settings::CNEWS_META_KEYWORDS;
                    break;
                case 'brand':
                    $settingsTitle = Settings::BRANDS_META_TITLE;
                    $settingsDescription = Settings::BRANDS_META_DESCRIPTION;
                    $settingsKeywords = Settings::BRANDS_META_KEYWORDS;
                    break;
                case 'expo':
                    $settingsTitle = Settings::EXPO_META_TITLE;
                    $settingsDescription = Settings::EXPO_META_DESCRIPTION;
                    $settingsKeywords = Settings::EXPO_META_KEYWORDS;
                    break;
                default:
                    $settingsTitle = Settings::BOARD_META_TITLE;
                    $settingsDescription = Settings::BOARD_META_DESCRIPTION;
                    $settingsKeywords = Settings::BOARD_META_KEYWORDS;
            }

            $title = Yii::$app->settings->get($settingsTitle);
            $description = Yii::$app->settings->get($settingsDescription);
            $keywords = Yii::$app->settings->get($settingsKeywords);
        }
        $title = $title ?: $defaultTitle;
        $view->title = $title;

        if ($description) {
            $view->registerMetaTag(['name' => 'description', 'content' => $description]);
        }
        if ($keywords) {
            $view->registerMetaTag(['name' => 'keywords', 'content' => $keywords]);
        }
    }
}