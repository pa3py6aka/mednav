<?php

use core\helpers\BoardHelper;
use frontend\widgets\BoardCategoriesListWidget;
use frontend\widgets\RegionsModalWidget;
use yii\helpers\Html;
use yii\widgets\LinkPager;
use core\helpers\PaginationHelper;

/* @var $this yii\web\View */
/* @var $category \core\entities\Board\BoardCategory|null */
/* @var $geo \core\entities\Geo|null */
/* @var $categoryRegion \core\entities\Board\BoardCategoryRegion|null */
/* @var $provider \yii\data\ActiveDataProvider */

/* @var $board \core\entities\Board\Board */

$this->title = 'Главная';


?>
<div class="row">
    <div class="col-md-9 col-sm-9 col-xs-12" style="border: 0px solid #000;">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <?= BoardHelper::breadCrumbs($category, $geo) ?>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12"><h1><?= $category ? $category->name : '' ?></h1></div>
        </div>

        <?= BoardCategoriesListWidget::widget(['category' => $category, 'region' => $geo]) ?>

        <!-- content-block-->
        <div class="row">
            <div class="col-md-12"><div class="list-content-block">Block 1</div></div>
        </div>
        <!-- // content-block-->

        <?= BoardHelper::categoryDescriptionBlock('top', $category, $categoryRegion) ?>

        <div class="row">
            <div class="col-md-12">
                <div class="list-panel-sort">
                    <div style="float: left; margin-right: 15px;">
                        <form class="form-inline">
                            Тип: <select class="form-control input-sm">
                                <option>Все</option>
                                <option>Продам</option>
                                <option>Куплю</option>
                                <option>Сервис</option>
                            </select>
                        </form>
                    </div>
                    <div>
                        Сортировать по: <a href="#">Цена</a> <a href="#">Дата</a>
                        <span>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalRegion"><?= $geo ? $geo->name : 'Все регионы' ?></button>
                        </span>
                    </div>
                </div>


            </div>
        </div>

        <!-- context-block-->
        <div class="row">
            <div class="col-md-12"><div class="list-context-block">Контекстный блок 1</div></div>
        </div>
        <!-- // context-block-->

        <?php foreach ($provider->models as $board): ?>
            <div class="list-item">
                <div class="row">
                    <div class="col-md-2 col-sm-2 col-xs-12">
                        <a href="<?= $board->getUrl() ?>">
                            <img src="<?= $board->mainPhoto ? $board->mainPhoto->getUrl() : '/img/100.png' ?>" alt="<?= $board->title ?>" class="img-responsive">
                        </a>
                    </div>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <div class="text-col2">
                            <span class="do-item-bs"><?= $board->typeBoardParameter ? $board->typeBoardParameter->option->name : '' ?></span> <a href="<?= $board->getUrl() ?>"><?= Html::encode($board->name) ?></a>
                        </div>
                        <div class="desc-col"><?= Html::encode($board->note) ?></div>
                        <div class="list-vendor-info"><i class="glyphicon glyphicon-calendar btn-xs city-icon-grey"></i> <?= Yii::$app->formatter->asDate($board->created_at) ?> / <a href="#">ООО Компания оптовых цен НВ-Лаб</a> / <?= $board->geo->name ?> / <a href="<?= BoardHelper::categoryUrl($board->category, $geo) ?>" class="list-lnk"><?= $board->category->name ?></a></div>
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-12"><div class="price-col"><?= $board->getPriceString() ?></div></div>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- context-block-->
        <div class="row">
            <div class="col-md-12"><div class="list-context-block">Контекстный блок 2</div></div>
        </div>
        <!-- // context-block-->

        <div class="list-pagination">
            <?php if ($category && $category->pagination == PaginationHelper::PAGINATION_NUMERIC): ?>
                <?= LinkPager::widget([
                    'pagination' => $provider->pagination
                ]) ?>
            <?php else: ?>
                <br>
                <p id="list-btn-scroll" class="btn btn-list" data-page="<?= $provider->pagination->page + 1 ?>">Показать ещё</p>
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

        <?= BoardHelper::categoryDescriptionBlock('bottom', $category, $categoryRegion) ?>

    </div>

    <!-- right col -->
    <div class="col-md-3 col-sm-3 hidden-xs">
        <div id="rightCol">
            <div style="margin: 10px 0;"><img src="/img/234.png" class="img-responsive" alt=""></div>
            <div class="sidebar-title">Популярные (строка)</div>
            <div class="row">
                <div class="col-md-4"><div class="sidebar-block-string-img"><a href="#"><img src="/img/417.jpg" alt="" class="img-responsive"></a></div></div>
                <div class="col-md-8"><div class="text-col"><a href="#">Ферментер лабораторный 3 шт. с доп. оборудованием</a></div>
                    <div class="price-col">12 354 000 руб./шт.</div>
                    <div class="desc-col">Небольшое уточнение для товара, г. Москва, состояние - новое.</div>
                    <div class="sidebar-item-vendinfo"><a href="#">ООО Новая объединенная компания 2017</a> / г. Москва</div>
                </div>
            </div>

            <div class="sidebar-item-string">
                <div class="row">
                    <div class="col-md-4"><div class="sidebar-block-string-img"><a href="#"><img src="/img/418.jpg" alt="" class="img-responsive"></a></div></div>
                    <div class="col-md-8"><div class="text-col"><a href="#">Ферментер лабораторный 3 шт. с доп. оборудованием</a></div>
                        <div class="price-col">12 354 000 руб./шт.</div>
                        <div class="desc-col">Небольшое уточнение для товара, г. Москва, состояние - новое.</div>
                        <div class="sidebar-item-vendinfo"><a href="#">ООО Новая объединенная компания 2017</a> / г. Москва</div>
                    </div>
                </div>
            </div>
            <div class="sidebar-item-string">
                <div class="row">
                    <div class="col-md-4"><div class="sidebar-block-string-img"><a href="#"><img src="/img/417.jpg" alt="" class="img-responsive"></a></div></div>
                    <div class="col-md-8"><div class="text-col"><a href="#">Установка для приготовления водного экстракта из засушенных или замороженных пантов марала - "Пант-Эра"</a></div>
                        <div class="price-col">12 354 000 руб./шт.</div>
                        <div class="desc-col">Небольшое уточнение для товара, г. Москва, состояние - новое.</div>
                        <div class="sidebar-item-vendinfo"><a href="#">ООО Новая объединенная компания 2017</a> / г. Москва</div>
                    </div>
                </div>
            </div>
            <div class="sidebar-item-string">
                <div class="row">
                    <div class="col-md-4"><div class="sidebar-block-string-img"><a href="#"><img src="/img/418.jpg" alt="" class="img-responsive"></a></div></div>
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

<?= RegionsModalWidget::widget(['category' => $category]) ?>
