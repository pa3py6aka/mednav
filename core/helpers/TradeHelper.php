<?php

namespace core\helpers;


use core\components\SettingsManager;
use core\entities\Geo;
use core\entities\Trade\Trade;
use core\entities\Trade\TradeCategory;
use Yii;
use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

class TradeHelper
{
    public static function breadCrumbs(TradeCategory $category = null, Geo $geo = null)
    {
        $items[] = ['label' => Yii::$app->settings->get(SettingsManager::TRADE_NAME), 'url' => ['/trade/trade/list', 'region' => $geo ? $geo->slug : 'all']];
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

    public static function itemBreadcrumbs(Trade $trade)
    {
        ?>
        <ul class="breadcrumb">
            <li><a href="<?= Yii::$app->homeUrl ?>">Главная</a></li>
            <li><a href="<?= Url::to(['/trade/trade/list', 'region' => Yii::$app->session->get('geo', 'all')]) ?>"><?= Yii::$app->settings->get(SettingsManager::TRADE_NAME) ?></a></li>
            <?php foreach ($trade->category->parents as $category) {
                if ($category->isRoot()) {
                    continue;
                }
                ?><li><a href="<?= self::categoryUrl($category, Yii::$app->session->get('geo', 'all')) ?>"><?= $category->getTitle() ?></a></li><?php
            } ?>
            <li><a href="<?= self::categoryUrl($trade->category, Yii::$app->session->get('geo', 'all')) ?>"><?= $trade->category->getTitle() ?></a></li>
        </ul>
        <?php
    }

    public static function contextCategoryLink(Trade $trade): string
    {
        $name = $trade->category->context_name ?: $trade->category->name;
        return Html::a($name, self::categoryUrl($trade->category, Yii::$app->session->get('geo', 'all')), ['class' => 'list-lnk']);
    }

    public static function categoryUrl(TradeCategory $category = null, $geo = null): string
    {
        if (!$category && !$geo) {
            $url = ['/trade/trade/list'];
        } else {
            $geoSlug = $geo && $geo instanceof Geo ? $geo->slug : ($geo ?: 'all');
            $url = ['/trade/trade/list', 'category' => $category ? $category->slug : null, 'region' => $geoSlug];
        }

        return Url::to($url);
    }

    public static function getCountInCategory(TradeCategory $category, $regionId = null): int
    {
        $query = Trade::find()
            ->alias('t')
            ->leftJoin(TradeCategory::tableName() . ' c', 't.category_id=c.id')
            ->active('t')
            ->andWhere(['>=', 'c.lft', $category->lft])
            ->andWhere(['<=', 'c.rgt', $category->rgt]);

        if ($regionId) {
            $query->andWhere(['t.geo_id' => $regionId]);
        }

        return $query->count();
    }
}