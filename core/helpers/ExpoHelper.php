<?php

namespace core\helpers;


use core\components\SettingsManager;
use core\entities\Expo\Expo;
use core\entities\Expo\ExpoCategory;
use Yii;
use yii\bootstrap\Html;
use yii\helpers\Url;

class ExpoHelper
{
    public static function itemBreadcrumbs(Expo $expo)
    {
        ?>
        <ul class="breadcrumb">
            <li>
                <a href="<?= Yii::$app->homeUrl ?>">Главная</a>
            </li>
            <li>
                <a href="<?= Url::to(['/brand/brand/list']) ?>">
                    <?= Yii::$app->settings->get(SettingsManager::EXPO_NAME) ?>
                </a>
            </li>
            <?php foreach ($expo->category->parents as $category) {
                if ($category->isRoot()) {
                    continue;
                }
                ?><li><a href="<?= self::categoryUrl($category) ?>"><?= $category->getTitle() ?></a></li><?php
            } ?>
            <li>
                <a href="<?= self::categoryUrl($expo->category) ?>"><?= $expo->category->getTitle() ?></a>
            </li>
        </ul>
        <?php
    }

    public static function contextCategoryLink(Expo $expo): string
    {
        $name = $expo->category->context_name ?: $expo->category->name;
        return Html::a($name, self::categoryUrl($expo->category), ['class' => 'list-lnk']);
    }

    public static function categoryUrl(ExpoCategory $category = null): string
    {
        if (!$category) {
            $url = ['/expo/expo/list'];
        } else {
            $url = ['/expo/expo/list', 'category' => $category ? $category->slug : null];
        }

        return Url::to($url);
    }

    public static function getCountInCategory(ExpoCategory $category): int
    {
        $query = Expo::find()
            ->alias('a')
            ->leftJoin(ExpoCategory::tableName() . ' c', 'a.category_id=c.id')
            ->active('a')
            ->andWhere(['>=', 'c.lft', $category->lft])
            ->andWhere(['<=', 'c.rgt', $category->rgt]);

        return $query->count();
    }
}