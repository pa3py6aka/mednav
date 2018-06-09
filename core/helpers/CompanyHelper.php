<?php

namespace core\helpers;


use core\components\SettingsManager;
use core\entities\Company\Company;
use core\entities\Company\CompanyCategory;
use core\entities\Company\CompanyCategoryAssignment;
use core\entities\Company\CompanyCategoryRegion;
use core\entities\Geo;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\Breadcrumbs;

class CompanyHelper
{
    public static function registerHeadMeta(View $view, CompanyCategory $category = null, CompanyCategoryRegion $categoryRegion = null): void
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
            $title = Yii::$app->settings->get(SettingsManager::COMPANY_META_TITLE);
            $description = Yii::$app->settings->get(SettingsManager::COMPANY_META_DESCRIPTION);
            $keywords = Yii::$app->settings->get(SettingsManager::COMPANY_META_KEYWORDS);
        }
        $title = $title ?: 'Компании';

        $view->title = $title;
        if ($description) {
            $view->registerMetaTag(['name' => 'description', 'content' => $description]);
        }
        if ($keywords) {
            $view->registerMetaTag(['name' => 'keywords', 'content' => $keywords]);
        }
    }

    public static function breadCrumbs(CompanyCategory $category = null, Geo $geo = null)
    {
        $items[] = ['label' => Yii::$app->settings->get(SettingsManager::COMPANY_NAME), 'url' => ['/company/company/list', 'region' => $geo ? $geo->slug : 'all']];
        if ($category) {
            foreach ($category->parents as $parent) {
                if ($parent->isRoot()) {
                    continue;
                }
                $items[] = ['label' => $parent->name, 'url' => self::categoryUrl($parent, $geo)];
            }
            $items[] = ['label' => $category->name, 'url' => self::categoryUrl($category, $geo)];
        }

        return Breadcrumbs::widget(['links' => $items]);
    }

    public static function categoryUrl(CompanyCategory $category = null, $geo = null)
    {
        if (!$category && !$geo) {
            $url = ['/company/company/list'];
        } else {
            $geoSlug = $geo && $geo instanceof Geo ? $geo->slug : ($geo ?: 'all');
            $url = ['/company/company/list', 'category' => $category ? $category->slug : null, 'region' => $geoSlug];
        }

        return Url::to($url);
    }

    public static function getCountInCategory(CompanyCategory $category, $regionId = null): int
    {
        $ids = ArrayHelper::merge([$category->id], $category->getDescendants()->select('id')->cache(300)->column());

        if ($regionId) {
            $query = Company::find()
                ->alias('c')
                ->leftJoin(CompanyCategoryAssignment::tableName() . ' cca', 'c.id=cca.company_id')
                ->andWhere(['cca.category_id' => $ids, 'c.geo_id' => $regionId]);
        } else {
            $query = CompanyCategoryAssignment::find()
                ->where(['category_id' => $ids]);
        }

        return $query->count();
    }
}