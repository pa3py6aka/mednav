<?php

namespace core\helpers;


use core\components\SettingsManager;
use core\entities\Board\BoardCategory;
use core\entities\Board\BoardCategoryRegion;
use core\entities\CategoryInterface;
use core\entities\Company\CompanyCategory;
use core\entities\Company\CompanyCategoryRegion;
use core\entities\Trade\TradeCategory;
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
     * @param BoardCategory|TradeCategory|CompanyCategory|null $category
     * @param BoardCategoryRegion|TradeCategoryRegion|CompanyCategoryRegion|null $categoryRegion
     */
    public static function registerHeadMeta($module, View $view, $defaultTitle, $category = null, $categoryRegion = null): void
    {
        $title = null;
        $description = null;
        $keywords = null;
        if ($categoryRegion) {
            $title = $categoryRegion->meta_title;
            $description = $categoryRegion->meta_description;
            $keywords = $categoryRegion->meta_keywords;
        }
        if ($category) {
            $title = $title ?: ($category->meta_title ?: ($category->title ?: $category->name));
            $description = $description ?: $category->meta_description;
            $keywords = $keywords ?: $category->meta_keywords;
        }
        if (!$category && !$categoryRegion) {
            switch ($module) {
                case 'company':
                    $settingsTitle = SettingsManager::COMPANY_META_TITLE;
                    $settingsDescription = SettingsManager::COMPANY_META_DESCRIPTION;
                    $settingsKeywords = SettingsManager::COMPANY_META_KEYWORDS;
                    break;
                case 'trade':
                    $settingsTitle = SettingsManager::TRADE_META_TITLE;
                    $settingsDescription = SettingsManager::TRADE_META_DESCRIPTION;
                    $settingsKeywords = SettingsManager::TRADE_META_KEYWORDS;
                    break;
                case 'article':
                    $settingsTitle = SettingsManager::ARTICLE_META_TITLE;
                    $settingsDescription = SettingsManager::ARTICLE_META_DESCRIPTION;
                    $settingsKeywords = SettingsManager::ARTICLE_META_KEYWORDS;
                    break;
                case 'news':
                    $settingsTitle = SettingsManager::NEWS_META_TITLE;
                    $settingsDescription = SettingsManager::NEWS_META_DESCRIPTION;
                    $settingsKeywords = SettingsManager::NEWS_META_KEYWORDS;
                    break;
                case 'cnews':
                    $settingsTitle = SettingsManager::CNEWS_META_TITLE;
                    $settingsDescription = SettingsManager::CNEWS_META_DESCRIPTION;
                    $settingsKeywords = SettingsManager::CNEWS_META_KEYWORDS;
                    break;
                case 'brand':
                    $settingsTitle = SettingsManager::BRANDS_META_TITLE;
                    $settingsDescription = SettingsManager::BRANDS_META_DESCRIPTION;
                    $settingsKeywords = SettingsManager::BRANDS_META_KEYWORDS;
                    break;
                default:
                    $settingsTitle = SettingsManager::BOARD_META_TITLE;
                    $settingsDescription = SettingsManager::BOARD_META_DESCRIPTION;
                    $settingsKeywords = SettingsManager::BOARD_META_KEYWORDS;
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