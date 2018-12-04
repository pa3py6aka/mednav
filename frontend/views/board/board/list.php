<?php

use core\helpers\BoardHelper;
use core\helpers\CategoryHelper;
use core\helpers\HtmlHelper;
use frontend\widgets\BoardCategoriesListWidget;
use frontend\widgets\RegionsModalWidget;
use yii\helpers\Html;
use yii\widgets\LinkPager;
use core\helpers\PaginationHelper;
use core\components\SettingsManager;
use core\components\ContextBlock;
use frontend\widgets\ContentBlock\ShowContentBlock;
use core\entities\ContentBlock;

/* @var $this yii\web\View */
/* @var $category \core\entities\Board\BoardCategory|null */
/* @var $geo \core\entities\Geo|null */
/* @var $categoryRegion \core\entities\Board\BoardCategoryRegion|null */
/* @var $provider \yii\data\ActiveDataProvider */
/* @var $type int */

CategoryHelper::registerHeadMeta('board', $this, 'Объявления', $category, $categoryRegion);

?>
<div class="row">
    <div class="col-md-9 col-sm-9 col-xs-12" style="border: 0px solid #000;">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <?= BoardHelper::breadCrumbs($category, $geo) ?>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12"><h1><?= HtmlHelper::getTitleForList(SettingsManager::BOARD_TITLE, $category, $categoryRegion, $provider->pagination->page) ?></h1></div>
        </div>

        <?= BoardCategoriesListWidget::widget(['category' => $category, 'region' => $geo]) ?>

        <?= ShowContentBlock::widget([
            'module' => ContentBlock::MODULE_BOARD,
            'place' => ContentBlock::PLACE_MAIN,
            'page' => ContentBlock::PAGE_LISTING,
            'category' => $category,
            'count' => 1,
        ]) ?>

        <?= HtmlHelper::categoryDescriptionBlock('top', SettingsManager::BOARD_DESCRIPTION_TOP, !$provider->pagination->page, $category, $categoryRegion, $geo) ?>

        <div class="row">
            <div class="col-md-12">
                <div class="list-panel-sort">
                    <div style="float: left; margin-right: 15px;">
                        <?= Html::beginForm(/*BoardHelper::categoryUrl($category, $geo, true)*/ \core\helpers\UrlHelper::getUrl(['/board/board/list'], 'type'), 'get', ['class' => 'form-inline filter-form-auto']) ?>
                            Тип: <?= Html::dropDownList('type', $type, BoardHelper::typeParameterOptions(), ['class' => 'form-control input-sm', 'prompt' => 'Все']) ?>
                        <?= Html::endForm() ?>
                    </div>
                    <div>
                        Сортировать по: <?= $provider->sort->link('price', ['class' => 'sort']) ?> &nbsp; <?= $provider->sort->link('date', ['class' => 'sort']) ?>
                        <span>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalRegion"><?= $geo ? $geo->name : 'Все регионы' ?></button>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <?= ContextBlock::getBlock(1, null, $category && $category->pagination == PaginationHelper::PAGINATION_NUMERIC) ?>

        <div class="card-items-block">
            <?= $this->render('card-items-block', [
                'provider' => $provider,
                'geo' => $geo,
            ]) ?>
        </div>

        <?= ContextBlock::getBlock(5) ?>

        <?= \frontend\widgets\PaginationWidget\PaginationWidget::widget([
            'provider' => $provider,
            'geo' => $geo,
            'category' => $category,
        ]) ?>

        <?= ShowContentBlock::widget([
            'module' => ContentBlock::MODULE_BOARD,
            'place' => ContentBlock::PLACE_MAIN,
            'page' => ContentBlock::PAGE_LISTING,
            'category' => $category,
            'start' => 2,
        ]) ?>

        <?= HtmlHelper::categoryDescriptionBlock('bottom', SettingsManager::BOARD_DESCRIPTION_BOTTOM, !$provider->pagination->page, $category, $categoryRegion) ?>

    </div>

    <!-- right col -->
    <div class="col-md-3 col-sm-3 hidden-xs">
        <div id="rightCol">
            <div style="margin: 10px 0;"><img src="/img/234.png" class="img-responsive" alt=""></div>

            <?= ShowContentBlock::widget([
                'module' => ContentBlock::MODULE_BOARD,
                'place' => ContentBlock::PLACE_SIDEBAR_RIGHT,
                'page' => ContentBlock::PAGE_LISTING,
                'category' => $category,
            ]) ?>

        </div><!-- // right col -->
    </div>
</div>

<?= RegionsModalWidget::widget(['category' => $category, 'type' => 'board']) ?>
