<?php

use core\helpers\CategoryHelper;
use core\helpers\ExpoHelper;
use core\helpers\HtmlHelper;
use frontend\widgets\CategoriesListWidget;
use core\helpers\PaginationHelper;
use core\components\SettingsManager;
use core\entities\Expo\ExpoCategory;
use frontend\widgets\ContentBlock\ShowContentBlock;
use core\entities\ContentBlock;
use core\components\ContextBlock;

/* @var $this yii\web\View */
/* @var $category \core\entities\Expo\ExpoCategory|null */
/* @var $provider \yii\data\ActiveDataProvider */

CategoryHelper::registerHeadMeta('expo', $this, 'Выставки', $category);
?>
<div class="row">
    <div class="col-md-9 col-sm-9 col-xs-12" style="border: 0px solid #000;">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <?= HtmlHelper::breadCrumbs(SettingsManager::EXPO_NAME, $category) ?>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12"><h1><?= HtmlHelper::getTitleForList(SettingsManager::EXPO_TITLE, $category, null, $provider->pagination->page) ?></h1></div>
        </div>

        <?= CategoriesListWidget::widget([
            'category' => $category,
            'component' => 'expo',
            'categoryClass' => ExpoCategory::class,
            'helperClass' => ExpoHelper::class,
        ]) ?>

        <?= ShowContentBlock::widget([
            'module' => ContentBlock::MODULE_EXPO,
            'page' => ContentBlock::PAGE_LISTING,
            'place' => ContentBlock::PLACE_MAIN,
            'category' => $category,
            'count' => 1
        ]) ?>

        <?= HtmlHelper::categoryDescriptionBlock('top', SettingsManager::EXPO_DESCRIPTION_TOP, !$provider->pagination->page, $category) ?>

        <?= ContextBlock::getBlock(1, null, $category && $category->pagination == PaginationHelper::PAGINATION_NUMERIC) ?>

        <div class="card-items-block">
            <?= $this->render('card-items-block', ['provider' => $provider]) ?>
        </div>

        <?= \frontend\widgets\PaginationWidget\PaginationWidget::widget([
            'provider' => $provider,
            'category' => $category,
        ]) ?>

        <?= ShowContentBlock::widget([
            'module' => ContentBlock::MODULE_EXPO,
            'place' => ContentBlock::PLACE_MAIN,
            'page' => ContentBlock::PAGE_LISTING,
            'category' => $category,
            'start' => 2,
        ]) ?>

        <?= HtmlHelper::categoryDescriptionBlock('bottom', SettingsManager::EXPO_DESCRIPTION_BOTTOM, !$provider->pagination->page, $category) ?>
    </div>

    <!-- right col -->
    <div class="col-md-3 col-sm-3 hidden-xs">
        <div id="rightCol">
            <div style="margin: 10px 0;"><img src="/img/234.png" class="img-responsive" alt=""></div>

            <?= ShowContentBlock::widget([
                'module' => ContentBlock::MODULE_EXPO,
                'place' => ContentBlock::PLACE_SIDEBAR_RIGHT,
                'page' => ContentBlock::PAGE_LISTING,
                'category' => $category,
            ]) ?>
        </div><!-- // right col -->
    </div>
</div>
