<?php

namespace core\helpers;


use core\components\SettingsManager;
use core\entities\Board\Board;
use core\entities\Board\BoardCategory;
use core\entities\Company\Company;
use core\entities\Company\CompanyCategory;
use core\entities\Company\CompanyCategoryAssignment;
use core\components\Settings;
use core\entities\Geo;
use core\entities\Trade\Trade;
use core\entities\Trade\TradeCategory;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

class CompanyHelper
{
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

    public static function categoryUrl(CompanyCategory $category = null, $geo = null): string
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

    public static function companyBreadcrumbs(Company $company, $page)
    {
        ?>
        <ul class="breadcrumb">
            <li><a href="<?= Yii::$app->homeUrl ?>">Главная</a></li>
            <li><a href="<?= self::categoryUrl() ?>"><?= Yii::$app->settings->get(Settings::COMPANY_NAME) ?></a></li>
            <li><a href="<?= $company->getUrl() ?>"><?= $company->getFullName() ?></a></li>
            <?php if ($page === 'contacts'): ?>
                <li><a href="<?= $company->getUrl('contacts') ?>">Контакты</a></li>
            <?php elseif ($page === 'boards'): ?>
                <li><a href="<?= $company->getUrl('boards') ?>">Объявления</a></li>
            <?php elseif ($page === 'trades'): ?>
                <li><a href="<?= $company->getUrl('trades') ?>">Товары</a></li>
            <?php elseif ($page === 'articles'): ?>
                <li><a href="<?= $company->getUrl('articles') ?>">Статьи</a></li>
            <?php elseif ($page === 'cnews'): ?>
                <li><a href="<?= $company->getUrl('cnews') ?>">Новости</a></li>
            <?php endif; ?>
        </ul>
        <?php
    }

    public static function companyBoardCategoriesItems(Company $company)
    {
        $query = (new Query())
            ->select('bc.*, COUNT(b.category_id) as b_count')
            ->from(BoardCategory::tableName() . ' bc')
            ->leftJoin(Board::tableName() . ' b', 'b.category_id=bc.id AND b.author_id=' . $company->user_id . ' AND b.status=' . Board::STATUS_ACTIVE . ' AND b.active_until>' . time())
            ->where(['>', 'bc.depth', 0])
            ->orderBy(['bc.lft' => SORT_DESC])
            ->groupBy('bc.id')
            ->all();

        $yes = 0;
        $categories = ArrayHelper::map($query, 'id', function (array $category) use (&$yes) {
            if ($category['b_count'] || $category['depth'] < $yes || ($category['depth'] == $yes && $category['b_count'])) {
                if ($category['b_count']) {
                    $yes = $category['depth'];
                }
                if ($category['depth'] == 1) {
                    $yes = 0;
                }

                return ($category['depth'] > 1 ? str_repeat('-', $category['depth'] - 1) . ' ' : '') . $category['name'] . ($category['b_count'] ? ' (' . $category['b_count'] . ')' : '');
            }
        });

        $categories = array_reverse(array_filter($categories), true);
        return $categories;
    }

    public static function companyTradeCategoriesItems(Company $company)
    {
        $query = (new Query())
            ->select('tc.*, COUNT(t.category_id) as t_count')
            ->from(TradeCategory::tableName() . ' tc')
            ->leftJoin(Trade::tableName() . ' t', 't.category_id=tc.id AND t.company_id=' . $company->id . ' AND t.status=' . Trade::STATUS_ACTIVE)
            ->where(['>', 'tc.depth', 0])
            ->orderBy(['tc.lft' => SORT_DESC])
            ->groupBy('tc.id')
            ->all();

        $yes = 0;
        $categories = ArrayHelper::map($query, 'id', function (array $category) use (&$yes) {
            if ($category['t_count'] || $category['depth'] < $yes || ($category['depth'] == $yes && $category['t_count'])) {
                if ($category['t_count']) {
                    $yes = $category['depth'];
                }
                if ($category['depth'] == 1) {
                    $yes = 0;
                }

                return ($category['depth'] > 1 ? str_repeat('-', $category['depth'] - 1) . ' ' : '') . $category['name'] . ($category['t_count'] ? ' (' . $category['t_count'] . ')' : '');
            }
        });

        $categories = array_reverse(array_filter($categories), true);
        return $categories;
    }

    public static function getDeliveriesString(Company $company, Geo $geo = null): string
    {
        if (!$geo) {
            return '';
        }

        $array = [];
        foreach ($company->deliveryRegions as $deliveryRegion) {
            if ($deliveryRegion->geo_id != $deliveryRegion->country_id) {
                $array[] = $deliveryRegion->geo_id;
            }
        }
        if (\count($array)) {
            return 'Доставка в ваш регион /';
        }
        return 'Доставка по России /';
    }

    public static function regionsSelectString($countryId, array $companyDeliveryRegions): string
    {
        $companyDeliveryRegions = array_filter($companyDeliveryRegions, function($v, $k) {
            return $v != $k;
        }, ARRAY_FILTER_USE_BOTH);
        $counts = array_count_values($companyDeliveryRegions);
        return isset($counts[$countryId]) && $counts[$countryId] > 0
            ? Pluralize::get($counts[$countryId], 'Выбран', 'Выбрано', 'Выбрано', true) . ' ' . Pluralize::get($counts[$countryId], 'регион', 'региона', 'регионов')
            : 'Выбрать регионы';
    }

    public static function pageTitle($title, Company $company): string
    {
        return $title . ' ' . $company->getFullName() . ($company->geo ? ', ' . $company->geo->name : '');
    }
}