<?php

use core\helpers\CategoryHelper;
use core\helpers\TradeHelper;
use core\helpers\HtmlHelper;
use frontend\widgets\CategoriesListWidget;
use frontend\widgets\RegionsModalWidget;
use core\components\ContextBlock;
use yii\widgets\LinkPager;
use core\helpers\PaginationHelper;
use core\components\SettingsManager;
use core\entities\Trade\TradeCategory;
use frontend\widgets\ContentBlock\ShowContentBlock;
use core\entities\ContentBlock;

/* @var $this yii\web\View */
/* @var $category \core\entities\Trade\TradeCategory|null */
/* @var $geo \core\entities\Geo|null */
/* @var $categoryRegion \core\entities\Trade\TradeCategoryRegion|null */
/* @var $provider \yii\data\ActiveDataProvider */


CategoryHelper::registerHeadMeta('trade', $this, 'Каталог товаров', $category, $categoryRegion);

?>
<div class="row">
    <div class="col-md-9 col-sm-9 col-xs-12" style="border: 0px solid #000;">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <?= TradeHelper::breadCrumbs($category, $geo) ?>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12"><h1><?= HtmlHelper::getTitleForList(SettingsManager::TRADE_TITLE, $category, $categoryRegion, $provider->pagination->page) ?></h1></div>
        </div>

        <?= CategoriesListWidget::widget([
            'category' => $category,
            'region' => $geo,
            'component' => 'company',
            'categoryClass' => TradeCategory::class,
            'helperClass' => TradeHelper::class,
        ]) ?>

        <?= ShowContentBlock::widget([
            'module' => ContentBlock::MODULE_TRADE,
            'place' => ContentBlock::PLACE_MAIN,
            'page' => ContentBlock::PAGE_LISTING,
            'count' => 1
        ]) ?>

        <?= HtmlHelper::categoryDescriptionBlock('top', SettingsManager::TRADE_DESCRIPTION_TOP, !$provider->pagination->page, $category, $categoryRegion, $geo) ?>

        <div class="row">
            <div class="col-md-12">
                <div class="list-panel-sort">
                    Сортировать по: <?= $provider->sort->link('price', ['class' => 'sort']) ?> <?= $provider->sort->link('id', ['class' => 'sort']) ?> <?= $provider->sort->link('stock', ['class' => 'sort']) ?>
                    <span>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalRegion"><?= $geo ? $geo->name : 'Все регионы' ?></button>
                    </span>
                </div>
            </div>
        </div>

        <?= ContextBlock::getBlock(1, null, $category && $category->pagination == PaginationHelper::PAGINATION_NUMERIC) ?>

        <div class="card-items-block">
            <?= $this->render('card-items-block', [
                'provider' => $provider,
                'geo' => $geo,
                'inCompany' => false,
            ]) ?>
        </div>

        <?= ContextBlock::getBlock(5) ?>

        <div class="list-pagination has-overlay">
            <?php if ($category && $category->pagination == PaginationHelper::PAGINATION_NUMERIC): ?>
                <?= LinkPager::widget([
                    'pagination' => $provider->pagination
                ]) ?>
            <?php elseif ($provider->pagination->pageCount > $provider->pagination->page + 1): ?>
                <br>
                <p id="list-btn-scroll" class="btn btn-list" data-url="<?= $provider->pagination->createUrl($provider->pagination->page + 1) ?>">Показать ещё</p>
            <?php endif; ?>
        </div>

        <?= ShowContentBlock::widget([
            'module' => ContentBlock::MODULE_TRADE,
            'place' => ContentBlock::PLACE_MAIN,
            'page' => ContentBlock::PAGE_LISTING,
            'start' => 2
        ]) ?>

        <?= HtmlHelper::categoryDescriptionBlock('bottom', SettingsManager::TRADE_DESCRIPTION_BOTTOM, !$provider->pagination->page, $category, $categoryRegion, $geo) ?>

    </div>

    <!-- right col -->
    <div class="col-md-3 col-sm-3 hidden-xs">
        <div id="rightCol">
            <div style="margin: 10px 0;"><img src="/img/234.png" class="img-responsive" alt=""></div>

            <?= ShowContentBlock::widget([
                'module' => ContentBlock::MODULE_TRADE,
                'place' => ContentBlock::PLACE_SIDEBAR_RIGHT,
                'page' => ContentBlock::PAGE_LISTING,
            ]) ?>
        </div><!-- // right col -->
    </div>
</div>

<?= RegionsModalWidget::widget(['category' => $category, 'type' => 'trade']) ?>
