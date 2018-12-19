<?php

use core\helpers\CategoryHelper;
use core\helpers\ArticleHelper;
use core\helpers\HtmlHelper;
use frontend\widgets\CategoriesListWidget;
use core\components\SettingsManager;
use core\entities\Article\ArticleCategory;
use frontend\widgets\ContentBlock\ShowContentBlock;
use core\helpers\PaginationHelper;
use core\entities\ContentBlock;
use core\components\ContextBlock;

/* @var $this yii\web\View */
/* @var $category \core\entities\Article\ArticleCategory|null */
/* @var $provider \yii\data\ActiveDataProvider */

CategoryHelper::registerHeadMeta('article', $this, 'Справочная информация', $category);
?>
<div class="row">
    <div class="col-md-9 col-sm-9 col-xs-12" style="border: 0px solid #000;">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <?= HtmlHelper::breadCrumbs(SettingsManager::ARTICLE_NAME, $category) ?>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12"><h1><?= HtmlHelper::getTitleForList(SettingsManager::ARTICLE_TITLE, $category, null, $provider->pagination->page) ?></h1></div>
        </div>

        <?= CategoriesListWidget::widget([
            'category' => $category,
            'component' => 'article',
            'categoryClass' => ArticleCategory::class,
            'helperClass' => ArticleHelper::class,
        ]) ?>

        <?= ShowContentBlock::widget([
            'module' => ContentBlock::MODULE_ARTICLE,
            'page' => ContentBlock::PAGE_LISTING,
            'place' => ContentBlock::PLACE_MAIN,
            'category' => $category,
            'count' => 1
        ]) ?>

        <?= HtmlHelper::categoryDescriptionBlock('top', SettingsManager::ARTICLE_DESCRIPTION_TOP, !$provider->pagination->page, $category) ?>

        <?= ContextBlock::getBlock(1, null, $category && $category->pagination == PaginationHelper::PAGINATION_NUMERIC) ?>

        <div class="card-items-block">
            <?= $this->render('card-items-block', ['provider' => $provider]) ?>
        </div>

        <?= ContextBlock::getBlock(5) ?>

        <?= \frontend\widgets\PaginationWidget\PaginationWidget::widget([
            'provider' => $provider,
            'category' => $category,
        ]) ?>

        <?= ShowContentBlock::widget([
            'module' => ContentBlock::MODULE_ARTICLE,
            'place' => ContentBlock::PLACE_MAIN,
            'page' => ContentBlock::PAGE_LISTING,
            'category' => $category,
            'start' => 2,
        ]) ?>

        <?= HtmlHelper::categoryDescriptionBlock('bottom', SettingsManager::ARTICLE_DESCRIPTION_BOTTOM, !$provider->pagination->page, $category) ?>

    </div>

    <!-- right col -->
    <div class="col-md-3 col-sm-3 hidden-xs">
        <div id="rightCol">
            <div style="margin: 10px 0;"><img src="/img/234.png" class="img-responsive" alt=""></div>

            <?= ShowContentBlock::widget([
                'module' => ContentBlock::MODULE_ARTICLE,
                'place' => ContentBlock::PLACE_SIDEBAR_RIGHT,
                'page' => ContentBlock::PAGE_LISTING,
                'category' => $category,
            ]) ?>
        </div><!-- // right col -->
    </div>
</div>
