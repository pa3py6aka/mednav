<?php

namespace core\helpers;


use core\components\SettingsManager;
use core\entities\Brand\Brand;
use core\entities\Brand\BrandCategory;
use Yii;
use yii\bootstrap\Html;
use yii\helpers\Url;

class BrandHelper
{
    public static function itemBreadcrumbs(Brand $brand)
    {
        ?>
        <ul class="breadcrumb">
            <li>
                <a href="<?= Yii::$app->homeUrl ?>">Главная</a>
            </li>
            <li>
                <a href="<?= Url::to(['/brand/brand/list']) ?>">
                    <?= Yii::$app->settings->get(SettingsManager::BRANDS_NAME) ?>
                </a>
            </li>
            <?php foreach ($brand->category->parents as $category) {
                if ($category->isRoot()) {
                    continue;
                }
                ?><li><a href="<?= self::categoryUrl($category) ?>"><?= $category->getTitle() ?></a></li><?php
            } ?>
            <li>
                <a href="<?= self::categoryUrl($brand->category) ?>"><?= $brand->category->getTitle() ?></a>
            </li>
        </ul>
        <?php
    }

    public static function contextCategoryLink(Brand $brand): string
    {
        $name = $brand->category->context_name ?: $brand->category->name;
        return Html::a($name, self::categoryUrl($brand->category), ['class' => 'list-lnk']);
    }

    public static function categoryUrl(BrandCategory $category = null): string
    {
        if (!$category) {
            $url = ['/brand/brand/list'];
        } else {
            $url = ['/brand/brand/list', 'category' => $category ? $category->slug : null];
        }

        return Url::to($url);
    }

    public static function getCountInCategory(BrandCategory $category): int
    {
        $query = Brand::find()
            ->alias('a')
            ->leftJoin(BrandCategory::tableName() . ' c', 'a.category_id=c.id')
            ->active('a')
            ->andWhere(['>=', 'c.lft', $category->lft])
            ->andWhere(['<=', 'c.rgt', $category->rgt]);

        return $query->count();
    }
}