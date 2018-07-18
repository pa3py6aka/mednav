<?php

use core\helpers\CategoryHelper;
use core\helpers\CompanyHelper;
use core\helpers\HtmlHelper;
use core\entities\Company\CompanyCategory;
use frontend\widgets\CategoriesListWidget;
use frontend\widgets\RegionsModalWidget;
use yii\helpers\Html;
use yii\widgets\LinkPager;
use core\helpers\PaginationHelper;
use core\components\SettingsManager;

/* @var $this yii\web\View */
/* @var $category \core\entities\Company\CompanyCategory|null */
/* @var $geo \core\entities\Geo|null */
/* @var $categoryRegion \core\entities\Company\CompanyCategoryRegion|null */
/* @var $provider \yii\data\ActiveDataProvider */


CategoryHelper::registerHeadMeta('company', $this, 'Компании', $category, $categoryRegion);

?>
<div class="row">
    <div class="col-md-9 col-sm-9 col-xs-12" style="border: 0px solid #000;">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <?= CompanyHelper::breadCrumbs($category, $geo) ?>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12"><h1><?= HtmlHelper::getTitleForList(SettingsManager::COMPANY_NAME, $category, $categoryRegion) ?></h1></div>
        </div>

        <?= CategoriesListWidget::widget([
            'category' => $category,
            'region' => $geo,
            'component' => 'company',
            'categoryClass' => CompanyCategory::class,
            'helperClass' => CompanyHelper::class,
        ]) ?>

        <!-- content-block-->
        <div class="row">
            <div class="col-md-12"><div class="list-content-block">Block 1</div></div>
        </div>
        <!-- // content-block-->

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

        <!-- context-block-->
        <div class="row">
            <div class="col-md-12"><div class="list-context-block">Контекстный блок 1</div></div>
        </div>
        <!-- // context-block-->

        <div class="card-items-block">
            <?= $this->render('card-items-block', [
                'provider' => $provider,
            ]) ?>
        </div>

        <!-- context-block-->
        <div class="row">
            <div class="col-md-12"><div class="list-context-block">Контекстный блок 2</div></div>
        </div>
        <!-- // context-block-->

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

        <!-- content-blocks-->
        <div class="row">
            <div class="col-md-12 col-sm-12 hidden-xs"><div class="list-content-block">Block 2</div></div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 hidden-xs"><div class="list-content-block">Block 3</div></div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 hidden-xs"><div class="list-content-block">Block 4</div></div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 hidden-xs"><div class="list-content-block">Block 5</div></div>
        </div>
        <!-- // content-blocks-->

        <?= HtmlHelper::categoryDescriptionBlock('bottom', SettingsManager::COMPANY_DESCRIPTION_BOTTOM, !$provider->pagination->page, $category, $categoryRegion) ?>

    </div>

    <!-- right col -->
    <div class="col-md-3 col-sm-3 hidden-xs">
        <div id="rightCol">
            <div style="margin: 10px 0;"><img src="img/234.png" class="img-responsive" alt=""></div>
            <div class="sidebar-title">Популярные (строка)</div>
            <div class="row">
                <div class="col-md-4"><div class="sidebar-block-string-img"><a href="#"><img src="img/417.jpg" alt="" class="img-responsive"></a></div></div>
                <div class="col-md-8"><div class="text-col"><a href="#">Ферментер лабораторный 3 шт. с доп. оборудованием</a></div>
                    <div class="price-col">12 354 000 руб./шт.</div>
                    <div class="desc-col">Небольшое уточнение для товара, г. Москва, состояние - новое.</div>
                    <div class="sidebar-item-vendinfo"><a href="#">ООО Новая объединенная компания 2017</a> / г. Москва</div>
                </div>
            </div>

            <div class="sidebar-item-string">
                <div class="row">
                    <div class="col-md-4"><div class="sidebar-block-string-img"><a href="#"><img src="img/418.jpg" alt="" class="img-responsive"></a></div></div>
                    <div class="col-md-8"><div class="text-col"><a href="#">Ферментер лабораторный 3 шт. с доп. оборудованием</a></div>
                        <div class="price-col">12 354 000 руб./шт.</div>
                        <div class="desc-col">Небольшое уточнение для товара, г. Москва, состояние - новое.</div>
                        <div class="sidebar-item-vendinfo"><a href="#">ООО Новая объединенная компания 2017</a> / г. Москва</div>
                    </div>
                </div>
            </div>
            <div class="sidebar-item-string">
                <div class="row">
                    <div class="col-md-4"><div class="sidebar-block-string-img"><a href="#"><img src="img/417.jpg" alt="" class="img-responsive"></a></div></div>
                    <div class="col-md-8"><div class="text-col"><a href="#">Установка для приготовления водного экстракта из засушенных или замороженных пантов марала - "Пант-Эра"</a></div>
                        <div class="price-col">12 354 000 руб./шт.</div>
                        <div class="desc-col">Небольшое уточнение для товара, г. Москва, состояние - новое.</div>
                        <div class="sidebar-item-vendinfo"><a href="#">ООО Новая объединенная компания 2017</a> / г. Москва</div>
                    </div>
                </div>
            </div>
            <div class="sidebar-item-string">
                <div class="row">
                    <div class="col-md-4"><div class="sidebar-block-string-img"><a href="#"><img src="img/418.jpg" alt="" class="img-responsive"></a></div></div>
                    <div class="col-md-8"><div class="text-col"><a href="#">Установка для приготовления водного экстракта из засушенных или замороженных пантов марала - "Пант-Эра"</a></div>
                        <div class="price-col">12 354 000 руб./шт.</div>
                        <div class="desc-col">Небольшое уточнение для товара, г. Москва, состояние - новое.</div>
                        <div class="sidebar-item-vendinfo"><a href="#">ООО Новая объединенная компания 2017</a> / г. Москва</div>
                    </div>
                </div>
            </div>
        </div><!-- // right col -->
    </div>
</div>

<?= RegionsModalWidget::widget(['category' => $category, 'type' => 'company']) ?>
