<?php

use core\helpers\CategoryHelper;
use core\helpers\ExpoHelper;
use core\helpers\HtmlHelper;
use frontend\widgets\CategoriesListWidget;
use yii\widgets\LinkPager;
use core\helpers\PaginationHelper;
use core\components\SettingsManager;
use core\entities\Expo\ExpoCategory;
use frontend\widgets\ContentBlock\ShowContentBlock;
use core\entities\ContentBlock;

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
            <div class="col-md-12 col-sm-12 col-xs-12"><h1><?= HtmlHelper::getTitleForList(SettingsManager::EXPO_NAME, $category) ?></h1></div>
        </div>

        <?= CategoriesListWidget::widget([
            'category' => $category,
            'component' => 'brand',
            'categoryClass' => ExpoCategory::class,
            'helperClass' => ExpoHelper::class,
        ]) ?>

        <?= HtmlHelper::categoryDescriptionBlock('top', SettingsManager::EXPO_DESCRIPTION_TOP, !$provider->pagination->page, $category) ?>

        <div class="card-items-block">
            <?= $this->render('card-items-block', ['provider' => $provider]) ?>
        </div>

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

        <?= HtmlHelper::categoryDescriptionBlock('bottom', SettingsManager::EXPO_DESCRIPTION_BOTTOM, !$provider->pagination->page, $category) ?>
    </div>

    <!-- right col -->
    <div class="col-md-3 col-sm-3 hidden-xs">
        <div id="rightCol">

        </div><!-- // right col -->
    </div>
</div>
