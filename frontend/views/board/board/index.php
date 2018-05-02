<?php

use core\helpers\BoardHelper;
use frontend\widgets\BoardCategoriesListWidget;
use frontend\widgets\RegionsModalWidget;

/* @var $this yii\web\View */
/* @var $category \core\entities\Board\BoardCategory|null */
/* @var $geo \core\entities\Geo|null */
/* @var $categoryRegion \core\entities\Board\BoardCategoryRegion|null */

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

        <div class="list-item">
            <div class="row">
                <div class="col-md-2 col-sm-2 col-xs-12"><a href="#"><img src="img/418.jpg" alt="" class="img-responsive"></a></div>
                <div class="col-md-8 col-sm-8 col-xs-12"><div class="text-col2"><span class="do-item-bs">Продам</span> <a href="#">Нить синяя полигликолидная с капролактоном (75:25) рассасывающая молофлатическая микровыступами 25 мм (полипропилен моно с микровыступами)</a></div>
                    <div class="desc-col">Небольшое уточнение для товара, г. Москва, состояние - новое.</div>
                    <div class="list-vendor-info"><i class="glyphicon glyphicon-calendar btn-xs city-icon-grey"></i> ДД-ММ-ГГГГ / <a href="#">ООО Компания оптовых цен НВ-Лаб</a> / г. Москва / <a href="#" class="list-lnk">Фетальные допплеры</a></div>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-12"><div class="price-col">125 340 000 руб.</div></div>
            </div>
        </div>

        <div class="list-item">
            <div class="row">
                <div class="col-md-2 col-sm-2 col-xs-12"><a href="#"><img src="img/418.jpg" alt="" class="img-responsive"></a></div>
                <div class="col-md-8 col-sm-8 col-xs-12"><div class="text-col2"><span class="do-item-bs">Продам</span> <a href="#">
                            Ферментер лабораторный 3 шт. с доп. оборудованием</a></div>
                    <div class="desc-col">Небольшое уточнение для товара, г. Москва, состояние - новое.</div>
                    <div class="list-vendor-info"><i class="glyphicon glyphicon-calendar btn-xs city-icon-grey"></i> ДД-ММ-ГГГГ / <a href="#">ООО Компания оптовых цен НВ-Лаб</a> / г. Москва / <a href="#" class="list-lnk">Фетальные допплеры</a></div>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-12"><div class="price-col">138 400 000 руб.</div></div>
            </div>
        </div>

        <div class="list-item">
            <div class="row">
                <div class="col-md-2 col-sm-2 col-xs-12"><a href="#"><img src="img/100.png" alt="" class="img-responsive"></a></div>
                <div class="col-md-8 col-sm-8 col-xs-12"><div class="text-col2"><span class="do-item-bs">Продам</span> <a href="#">Нить синяя полигликолидная с капролактоном (75:25) рассасывающая молофлатическая микровыступами 25 мм (полипропилен моно с микровыступами)</a></div>
                    <div class="desc-col">Небольшое уточнение для товара, г. Москва, состояние - новое.</div>
                    <div class="list-vendor-info"><i class="glyphicon glyphicon-calendar btn-xs city-icon-grey"></i> ДД-ММ-ГГГГ / <a href="#">ООО Компания оптовых цен НВ-Лаб</a> / г. Москва / <a href="#" class="list-lnk">Фетальные допплеры</a></div>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-12"><div class="price-col">138 400,50 руб.</div></div>
            </div>
        </div>
        <div class="list-item">
            <div class="row">
                <div class="col-md-2 col-sm-2 col-xs-12"><a href="#"><img src="img/417.jpg" alt="" class="img-responsive"></a></div>
                <div class="col-md-8 col-sm-8 col-xs-12"><div class="text-col2"><span class="do-item-bs">Продам</span> <a href="#">Нить синяя полигликолидная с капролактоном (75:25) рассасывающая молофлатическая микровыступами 25 мм (полипропилен моно с микровыступами)</a></div>
                    <div class="desc-col">Небольшое уточнение для товара, г. Москва, состояние - новое.</div>
                    <div class="list-vendor-info"><i class="glyphicon glyphicon-calendar btn-xs city-icon-grey"></i> ДД-ММ-ГГГГ / <a href="#">ООО Компания оптовых цен НВ-Лаб</a> / г. Москва / <a href="#" class="list-lnk">Фетальные допплеры</a></div>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-12"><div class="price-col">Звоните</div></div>
            </div>
        </div>
        <div class="list-item">
            <div class="row">
                <div class="col-md-2 col-sm-2 col-xs-12"><a href="#"><img src="img/100.png" alt="" class="img-responsive"></a></div>
                <div class="col-md-8 col-sm-8 col-xs-12"><div class="text-col2"><span class="do-item-bs">Продам</span> <a href="#">Нить синяя полигликолидная с капролактоном (75:25) рассасывающая молофлатическая микровыступами 25 мм (полипропилен моно с микровыступами)</a></div>
                    <div class="desc-col">Небольшое уточнение для товара, г. Москва, состояние - новое.</div>
                    <div class="list-vendor-info"><i class="glyphicon glyphicon-calendar btn-xs city-icon-grey"></i> ДД-ММ-ГГГГ / <a href="#">ООО Компания оптовых цен НВ-Лаб</a> / г. Москва / <a href="#" class="list-lnk">Фетальные допплеры</a></div>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-12"><div class="price-col">138 400,50 руб.</div></div>
            </div>
        </div>
        <!-- context-block-->
        <div class="row">
            <div class="col-md-12"><div class="list-context-block">Контекстный блок 2</div></div>
        </div>
        <!-- // context-block-->
        <div class="list-pagination">
            <ul class="pagination">
                <li class="disabled"><span>«</span></li>
                <li class="active"><span>1</span></li>
                <li><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#">4</a></li>
                <li><a href="#">5</a></li>
                <li><a href="#">»</a></li>
            </ul>
            <br/><br/>
            <p id="list-btn-scroll" class="btn btn-list">Показать ещё</p>

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

<?= RegionsModalWidget::widget(['category' => $category]) ?>
