<?php

use yii\helpers\Html;
use core\helpers\CompanyHelper;
use core\helpers\TextHelper;


/* @var $this \yii\web\View */
/* @var $company \core\entities\Company\Company */
/* @var $provider \yii\data\ActiveDataProvider */

$this->title = $company->getTitle();
$this->registerMetaTag(['name' => 'description', 'content' => Html::encode($company->description)]);
$this->registerMetaTag(['name' => 'keywords', 'content' => Html::encode($company->getTagsString())]);

?>
<div class="row">
    <div class="col-md-9 col-sm-9 col-xs-12">
        <!-- content-block-->
        <div class="row">
            <div class="col-md-12 col-sm-12 hidden-xs"><div class="item-content-block">Block 1</div></div>
        </div>

        <!-- // content-block-->
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <ul class="breadcrumb"><li><a href="#">Главная</a></li><li><a href="#">Каталог компаний</a></li><li>ООО Ультра-Мед</li></ul>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12"><h1>Объявления ООО Новая объединенная компания 2017</h1></div>
            <div class="col-md-12 col-sm-12 hidden-xs"><div class="kk-add-lnk hidden-xs"><span class="glyphicon glyphicon-envelope btn-xs icon-blue"></span><a href="#">Написать сообщение</a> <span class="glyphicon glyphicon-user btn-xs icon-blue"></span><a href="#">Добавить в контакты</a></div></div>
        </div>


        <div class="row">

            <div class="col-md-12">
                <div class="kk-menu">
                    <ul>
                        <li><a href="#">О компании</a></li>
                        <li><a href="#">Контакты</a></li>
                        <li><a href="#">Товары</a> <sup>1200</sup></li>
                        <li><a href="#">Объявления</a> <sup>1200</sup></li>
                        <li><a href="#">Новости компании</a> <sup>1200</sup></li>
                        <li><a href="#">Статьи</a> <sup>1200</sup></li>
                        <li><a href="#">Акции</a> <sup>1200</sup></li>
                    </ul>
                </div>

                <form class="form-inline">
                    Раздел: <select class="form-control input-md">
                        <option>Все</option>
                        <option>Гинекология(45)</option>
                        <option> &nbsp; - Оборудование(30)</option>
                        <option>  &nbsp; &nbsp; - Калькоскопы (15)</option>
                        <option>  &nbsp; &nbsp; - Калькоскопы 2 (15)</option>
                        <option>  &nbsp; &nbsp; - Калькоскопы 3 (15)</option>
                        <option>Акушесрство(45)</option>
                        <option> &nbsp; - Оборудование(45)</option>
                    </select>
                </form>

                <div class="list-item">
                    <div class="row">
                        <div class="col-md-2 col-sm-2 col-xs-12"><a href="#"><img src="img/418.jpg" alt="" class="img-responsive"></a></div>
                        <div class="col-md-8 col-sm-8 col-xs-12"><div class="text-col2"><span class="do-item-bs">Продам</span> <a href="#">Нить синяя полигликолидная с капролактоном (75:25) рассасывающая молофлатическая микровыступами 25 мм (полипропилен моно с микровыступами)</a></div>
                            <div class="desc-col">Небольшое уточнение для товара, г. Москва, состояние - новое.</div>
                            <div class="list-vendor-info"><i class="glyphicon glyphicon-calendar btn-xs city-icon-grey"></i> ДД-ММ-ГГГГ / <a href="#" class="list-lnk">Фетальные допплеры</a></div>
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
                            <div class="list-vendor-info"><i class="glyphicon glyphicon-calendar btn-xs city-icon-grey"></i> ДД-ММ-ГГГГ / <a href="#" class="list-lnk">Фетальные допплеры</a></div>
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-12"><div class="price-col">138 400 000 руб.</div></div>
                    </div>
                </div>

                <div class="list-item">
                    <div class="row">
                        <div class="col-md-2 col-sm-2 col-xs-12"><a href="#"><img src="img/100.png" alt="" class="img-responsive"></a></div>
                        <div class="col-md-8 col-sm-8 col-xs-12"><div class="text-col2"><span class="do-item-bs">Продам</span> <a href="#">Нить синяя полигликолидная с капролактоном (75:25) рассасывающая молофлатическая микровыступами 25 мм (полипропилен моно с микровыступами)</a></div>
                            <div class="desc-col">Небольшое уточнение для товара, г. Москва, состояние - новое.</div>
                            <div class="list-vendor-info"><i class="glyphicon glyphicon-calendar btn-xs city-icon-grey"></i> ДД-ММ-ГГГГ / <a href="#" class="list-lnk">Фетальные допплеры</a></div>
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-12"><div class="price-col">138 400,50 руб.</div></div>
                    </div>
                </div>
                <div class="list-item">
                    <div class="row">
                        <div class="col-md-2 col-sm-2 col-xs-12"><a href="#"><img src="img/417.jpg" alt="" class="img-responsive"></a></div>
                        <div class="col-md-8 col-sm-8 col-xs-12"><div class="text-col2"><span class="do-item-bs">Продам</span> <a href="#">Нить синяя полигликолидная с капролактоном (75:25) рассасывающая молофлатическая микровыступами 25 мм (полипропилен моно с микровыступами)</a></div>
                            <div class="desc-col">Небольшое уточнение для товара, г. Москва, состояние - новое.</div>
                            <div class="list-vendor-info"><i class="glyphicon glyphicon-calendar btn-xs city-icon-grey"></i> ДД-ММ-ГГГГ / <a href="#" class="list-lnk">Фетальные допплеры</a></div>
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-12"><div class="price-col">Звоните</div></div>
                    </div>
                </div>
                <div class="list-item">
                    <div class="row">
                        <div class="col-md-2 col-sm-2 col-xs-12"><a href="#"><img src="img/100.png" alt="" class="img-responsive"></a></div>
                        <div class="col-md-8 col-sm-8 col-xs-12"><div class="text-col2"><span class="do-item-bs">Продам</span> <a href="#">Нить синяя полигликолидная с капролактоном (75:25) рассасывающая молофлатическая микровыступами 25 мм (полипропилен моно с микровыступами)</a></div>
                            <div class="desc-col">Небольшое уточнение для товара, г. Москва, состояние - новое.</div>
                            <div class="list-vendor-info"><i class="glyphicon glyphicon-calendar btn-xs city-icon-grey"></i> ДД-ММ-ГГГГ / <a href="#" class="list-lnk">Фетальные допплеры</a></div>
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-12"><div class="price-col">138 400,50 руб.</div></div>
                    </div>
                </div>
                <div class="list-pagination">
                    <p id="list-btn-scroll" class="btn btn-list">Показать ещё</p>
                </div>
            </div>


        </div>


        <div class="row">
            <div class="md-12 col-sm-12 col-xs-12">
                <div class="sidebar-title">Похожие товары из <a href="#">Название раздела</a> (Block 2)</div>
                <div class="brand-baner">
                    <div class="brand-baner__slider owl-carousel-2  owl-carousel">
                        <div class="brand-baner__item">
                            <div class="content-block-caorusel-img"><a href="#"><img src="img/418.jpg" alt="" align="center" class="img-responsive"></a></div>
                            <div class="text-col"><a href="#">Ферментер лабораторный 3 шт. с доп. оборудованием</a></div>
                            <div class="price-col">1 250 000 руб./шт.</div>
                            <div class="desc-col">Нить синяя полигликолидная</div>
                        </div>

                        <div class="brand-baner__item">
                            <div class="content-block-caorusel-img"><a href="#"><img src="img/417.jpg" alt="" align="center" class="img-responsive"></a></div>
                            <div class="text-col"><a href="#">Нить синяя полигликолидная с капролактоном (75:25) рассасывающая...</a></div>
                            <div class="price-col">1 250 000 руб./шт.</div>
                            <div class="desc-col">Нить синяя полигликолидная</div>
                        </div>

                        <div class="brand-baner__item">
                            <div class="content-block-caorusel-img"><a href="#"><img src="img/417.jpg" alt="" align="center" class="img-responsive"></a></div>
                            <div class="text-col"><a href="#">Нить синяя полигликолидная с капролактоном (75:25) рассасывающая...</a></div>
                            <div class="price-col">1 250 000 руб./шт.</div>
                            <div class="desc-col">Нить синяя полигликолидная</div>
                        </div>

                        <div class="brand-baner__item">
                            <div class="content-block-caorusel-img"><a href="#"><img src="img/417.jpg" alt="" align="center" class="img-responsive"></a></div>
                            <div class="text-col"><a href="#">Нить синяя полигликолидная с капролактоном (75:25) рассасывающая...</a></div>
                            <div class="price-col">1 250 000 руб./шт.</div>
                            <div class="desc-col">Нить синяя полигликолидная</div>
                        </div>

                        <div class="brand-baner__item">
                            <div class="content-block-caorusel-img"><a href="#"><img src="img/417.jpg" alt="" align="center" class="img-responsive"></a></div>
                            <div class="text-col"><a href="#">Нить синяя полигликолидная с капролактоном (75:25) рассасывающая...</a></div>
                            <div class="price-col">1 250 000 руб./шт.</div>
                            <div class="desc-col">Нить синяя полигликолидная</div>
                        </div>

                        <div class="brand-baner__item">
                            <div class="content-block-caorusel-img"><a href="#"><img src="img/417.jpg" alt="" align="center" class="img-responsive"></a></div>
                            <div class="text-col"><a href="#">Нить синяя полигликолидная с капролактоном (75:25) рассасывающая...</a></div>
                            <div class="price-col">1 250 000 руб./шт.</div>
                            <div class="desc-col">Нить синяя полигликолидная</div>
                        </div>

                        <div class="brand-baner__item">
                            <div class="content-block-caorusel-img"><a href="#"><img src="img/417.jpg" alt="" align="center" class="img-responsive"></a></div>
                            <div class="text-col"><a href="#">Нить синяя полигликолидная с капролактоном (75:25) рассасывающая...</a></div>
                            <div class="price-col">1 250 000 руб./шт.</div>
                            <div class="desc-col">Нить синяя полигликолидная</div>
                        </div>

                        <div class="brand-baner__item">
                            <div class="content-block-caorusel-img"><a href="#"><img src="img/417.jpg" alt="" align="center" class="img-responsive"></a></div>
                            <div class="text-col"><a href="#">Нить синяя полигликолидная с капролактоном (75:25) рассасывающая...</a></div>
                            <div class="price-col">1 250 000 руб./шт.</div>
                            <div class="desc-col">Нить синяя полигликолидная</div>
                        </div>

                        <div class="brand-baner__item">
                            <div class="content-block-caorusel-img"><a href="#"><img src="img/417.jpg" alt="" align="center" class="img-responsive"></a></div>
                            <div class="text-col"><a href="#">Нить синяя полигликолидная с капролактоном (75:25) рассасывающая...</a></div>
                            <div class="price-col">1 250 000 руб./шт.</div>
                            <div class="desc-col">Нить синяя полигликолидная</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- content-blocks-->
        <div class="row">
            <div class="col-md-12 col-sm-12 hidden-xs"><div class="item-content-block">Block 3</div></div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 hidden-xs"><div class="item-content-block">Block 4</div></div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 hidden-xs"><div class="item-content-block">Block 5</div></div>
        </div>
        <!-- // content-block-->
        <script type="text/javascript">
            $(".owl-carousel-2").owlCarousel({
                loop: true,
                nav: true,
                dots: false,
                autoplay: false,
                //margin: 20,
                navText: true,
                smartSpeed: 1000, //Время движения слайда
                autoplayTimeout: 4000, //Время смены слайда
                pagination: false,
                responsiveClass: true,
                responsive: {
                    1000: {
                        items: 4
                    },
                    600: {
                        items: 3
                    },
                    0: {
                        items: 1
                    }


                }
            });
        </script>




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
