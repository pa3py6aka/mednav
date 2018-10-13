<?php

namespace core\helpers;


use core\components\SettingsManager;
use core\entities\Geo;
use core\entities\Article\Article;
use core\entities\Article\ArticleCategory;
use Yii;
use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

class ArticleHelper
{
    public static function itemBreadcrumbs(Article $article)
    {
        ?>
        <ul class="breadcrumb">
            <li>
                <a href="<?= Yii::$app->homeUrl ?>">Главная</a>
            </li>
            <li>
                <a href="<?= Url::to(['/article/article/list']) ?>">
                    <?= Yii::$app->settings->get(SettingsManager::ARTICLE_NAME) ?>
                </a>
            </li>
            <?php foreach ($article->category->parents as $category) {
                if ($category->isRoot()) {
                    continue;
                }
                ?><li><a href="<?= self::categoryUrl($category) ?>"><?= $category->getTitle() ?></a></li><?php
            } ?>
            <li>
                <a href="<?= self::categoryUrl($article->category) ?>"><?= $article->category->getTitle() ?></a>
            </li>
        </ul>
        <?php
    }

    public static function contextCategoryLink(Article $article): string
    {
        $name = $article->category->context_name ?: $article->category->name;
        return Html::a($name, self::categoryUrl($article->category), ['class' => 'list-lnk']);
    }

    public static function categoryUrl(ArticleCategory $category = null): string
    {
        if (!$category) {
            $url = ['/article/article/list'];
        } else {
            $url = ['/article/article/list', 'category' => $category ? $category->slug : null];
        }

        return Url::to($url);
    }

    public static function getCountInCategory(ArticleCategory $category): int
    {
        $query = Article::find()
            ->alias('a')
            ->leftJoin(ArticleCategory::tableName() . ' c', 'a.category_id=c.id')
            ->active('a')
            ->andWhere(['>=', 'c.lft', $category->lft])
            ->andWhere(['<=', 'c.rgt', $category->rgt]);

        return $query->count();
    }
}