<?php

namespace core\helpers;


use core\components\SettingsManager;
use core\entities\News\News;
use core\entities\News\NewsCategory;
use Yii;
use yii\bootstrap\Html;
use yii\helpers\Url;

class NewsHelper
{
    public static function itemBreadcrumbs(News $news)
    {
        ?>
        <ul class="breadcrumb">
            <li>
                <a href="<?= Yii::$app->homeUrl ?>">Главная</a>
            </li>
            <li>
                <a href="<?= Url::to(['/news/news/list']) ?>">
                    <?= Yii::$app->settings->get(SettingsManager::NEWS_NAME) ?>
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

    public static function contextCategoryLink(News $news): string
    {
        $name = $news->category->context_name ?: $news->category->name;
        return Html::a($name, self::categoryUrl($news->category), ['class' => 'list-lnk']);
    }

    public static function categoryUrl(NewsCategory $category = null): string
    {
        if (!$category) {
            $url = ['/news/news/list'];
        } else {
            $url = ['/news/news/list', 'category' => $category ? $category->slug : null];
        }

        return Url::to($url);
    }

    public static function getCountInCategory(NewsCategory $category): int
    {
        $query = News::find()
            ->alias('a')
            ->leftJoin(NewsCategory::tableName() . ' c', 'a.category_id=c.id')
            ->active('a')
            ->andWhere(['>=', 'c.lft', $category->lft])
            ->andWhere(['<=', 'c.rgt', $category->rgt]);

        return $query->count();
    }
}