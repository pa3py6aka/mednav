<?php

namespace core\helpers;


use core\components\SettingsManager;
use core\entities\CNews\CNews;
use core\entities\CNews\CNewsCategory;
use Yii;
use yii\bootstrap\Html;
use yii\helpers\Url;

class CNewsHelper
{
    public static function itemBreadcrumbs(CNews $news)
    {
        ?>
        <ul class="breadcrumb">
            <li>
                <a href="<?= Yii::$app->homeUrl ?>">Главная</a>
            </li>
            <li>
                <a href="<?= Url::to(['/cnews/cnews/list']) ?>">
                    <?= Yii::$app->settings->get(SettingsManager::CNEWS_NAME) ?>
                </a>
            </li>
            <?php foreach ($news->category->parents as $category) {
                if ($category->isRoot()) {
                    continue;
                }
                ?><li><a href="<?= self::categoryUrl($category) ?>"><?= $category->getTitle() ?></a></li><?php
            } ?>
            <li>
                <a href="<?= self::categoryUrl($news->category) ?>"><?= $news->category->getTitle() ?></a>
            </li>
        </ul>
        <?php
    }

    public static function contextCategoryLink(CNews $news): string
    {
        $name = $news->category->context_name ?: $news->category->name;
        return Html::a($name, self::categoryUrl($news->category), ['class' => 'list-lnk']);
    }

    public static function categoryUrl(CNewsCategory $category = null): string
    {
        if (!$category) {
            $url = ['/cnews/cnews/list'];
        } else {
            $url = ['/cnews/cnews/list', 'category' => $category ? $category->slug : null];
        }

        return Url::to($url);
    }

    public static function getCountInCategory(CNewsCategory $category): int
    {
        $query = CNews::find()
            ->alias('a')
            ->leftJoin(CNewsCategory::tableName() . ' c', 'a.category_id=c.id')
            ->active('a')
            ->andWhere(['>=', 'c.lft', $category->lft])
            ->andWhere(['<=', 'c.rgt', $category->rgt]);

        return $query->count();
    }
}