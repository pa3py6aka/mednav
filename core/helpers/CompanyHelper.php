<?php

namespace core\helpers;


use core\components\SettingsManager;
use core\entities\Board\Board;
use core\entities\Board\BoardCategory;
use core\entities\Company\Company;
use core\entities\Company\CompanyCategory;
use core\entities\Company\CompanyCategoryAssignment;
use core\entities\Company\CompanyCategoryRegion;
use core\entities\Geo;
use core\entities\Trade\Trade;
use core\entities\Trade\TradeCategory;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\View;
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
            <li><a href="<?= self::categoryUrl() ?>">Каталог компаний</a></li>
            <li><a href="<?= $company->getUrl() ?>"><?= $company->getFullName() ?></a></li>
            <?php if ($page == 'contacts'): ?>
                <li><a href="<?= $company->getUrl('contacts') ?>">Контакты</a></li>
            <?php elseif ($page == 'boards'): ?>
                <li><a href="<?= $company->getUrl('boards') ?>">Объявления</a></li>
            <?php elseif ($page == 'trades'): ?>
                <li><a href="<?= $company->getUrl('trades') ?>">Товары</a></li>
            <?php endif; ?>
        </ul>
        <?php
    }

    public static function companyBoardCategoriesItems(Company $company)
    {
        $query = (new Query())
            ->select('bc.*, COUNT(b.category_id) as b_count')
            ->from(BoardCategory::tableName() . ' bc')
            ->leftJoin(Board::tableName() . ' b', 'b.category_id=bc.id')
            ->where(['b.author_id' => $company->user_id])
            ->andWhere(['b.status' => Board::STATUS_ACTIVE])
            ->andWhere(['>', 'b.active_until', time()])
            ->orderBy('bc.lft')
            ->groupBy('bc.id')
            ->all();

        $categories = ArrayHelper::map($query, 'id', function (array $category) {
            return ($category['depth'] > 1 ? str_repeat('-', $category['depth'] - 1) . ' ' : '') . $category['name'] . ' (' . $category['b_count'] . ')';
        });

        return $categories;
    }

    public static function companyTradeCategoriesItems(Company $company)
    {
        $query = (new Query())
            ->select('tc.*, COUNT(t.category_id) as t_count')
            ->from(TradeCategory::tableName() . ' tc')
            ->leftJoin(Trade::tableName() . ' t', 't.category_id=tc.id')
            ->where(['t.company_id' => $company->id])
            ->andWhere(['t.status' => Trade::STATUS_ACTIVE])
            ->orderBy('tc.lft')
            ->groupBy('tc.id')
            ->all();

        $categories = ArrayHelper::map($query, 'id', function (array $category) {
            return ($category['depth'] > 1 ? str_repeat('-', $category['depth'] - 1) . ' ' : '') . $category['name'] . ' (' . $category['t_count'] . ')';
        });

        return $categories;
    }
}