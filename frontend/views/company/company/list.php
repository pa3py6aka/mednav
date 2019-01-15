<?php

use core\helpers\CategoryHelper;
use core\helpers\CompanyHelper;
use core\helpers\HtmlHelper;
use core\entities\Company\CompanyCategory;
use frontend\widgets\CategoriesListWidget;
use frontend\widgets\RegionsModalWidget;
use core\components\ContextBlock;
use core\helpers\PaginationHelper;
use core\components\SettingsManager;
use frontend\widgets\ContentBlock\ShowContentBlock;
use core\entities\ContentBlock;

/* @var $this yii\web\View */
/* @var $category \core\entities\Company\CompanyCategory|null */
/* @var $geo \core\entities\Geo|null */
/* @var $categoryRegion \core\entities\Company\CompanyCategoryRegion|null */
/* @var $provider \yii\data\ActiveDataProvider */


CategoryHelper::registerHeadMeta('company', $this, 'Компании', $category, $categoryRegion, $geo);

?>
<div class="row">
    <div class="col-md-9 col-sm-9 col-xs-12" style="border: 0px solid #000;">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <?= CompanyHelper::breadCrumbs($category, $geo) ?>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12"><h1><?= HtmlHelper::getTitleForList(SettingsManager::COMPANY_TITLE, $category, $categoryRegion, $provider->pagination->page) ?></h1></div>
        </div>

        <?= CategoriesListWidget::widget([
            'category' => $category,
            'region' => $geo,
            'component' => 'company',
            'categoryClass' => CompanyCategory::class,
            'helperClass' => CompanyHelper::class,
        ]) ?>

        <?= ShowContentBlock::widget([
            'module' => ContentBlock::MODULE_COMPANY,
            'place' => ContentBlock::PLACE_MAIN,
            'page' => ContentBlock::PAGE_LISTING,
            'category' => $category,
            'count' => 1,
        ]) ?>

        <?= HtmlHelper::categoryDescriptionBlock('top', SettingsManager::COMPANY_DESCRIPTION_TOP, !$provider->pagination->page, $category, $categoryRegion) ?>

        <div class="row">
            <div class="col-md-12">
                <div class="list-panel-sort">
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
            ]) ?>
        </div>

        <?= count($provider->models) > 16 ? ContextBlock::getBlock(5) : '' ?>

        <?= \frontend\widgets\PaginationWidget\PaginationWidget::widget([
            'provider' => $provider,
            'geo' => $geo,
            'category' => $category,
        ]) ?>

        <?= ShowContentBlock::widget([
            'module' => ContentBlock::MODULE_COMPANY,
            'place' => ContentBlock::PLACE_MAIN,
            'page' => ContentBlock::PAGE_LISTING,
            'category' => $category,
            'start' => 2,
        ]) ?>

        <?= HtmlHelper::categoryDescriptionBlock('bottom', SettingsManager::COMPANY_DESCRIPTION_BOTTOM, !$provider->pagination->page, $category, $categoryRegion) ?>

    </div>

    <!-- right col -->
    <div class="col-md-3 col-sm-3 hidden-xs">
        <div id="rightCol">
            <div style="margin: 10px 0;"><img src="img/234.png" class="img-responsive" alt=""></div>

            <?= ShowContentBlock::widget([
                'module' => ContentBlock::MODULE_COMPANY,
                'place' => ContentBlock::PLACE_SIDEBAR_RIGHT,
                'page' => ContentBlock::PAGE_LISTING,
                'category' => $category,
            ]) ?>
        </div><!-- // right col -->
    </div>
</div>

<?= RegionsModalWidget::widget(['category' => $category, 'type' => 'company']) ?>