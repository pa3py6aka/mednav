<?php
use yii\helpers\Html;
use core\helpers\TradeHelper;
use core\helpers\TextHelper;
use core\helpers\PriceHelper;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $trade \core\entities\Trade\Trade */

$this->title = Html::encode($trade->meta_title ?: $trade->getTitle());
$this->registerMetaTag(['name' => 'description', 'content' => Html::encode($trade->description)]);
$this->registerMetaTag(['name' => 'keywords', 'content' => Html::encode($trade->meta_keywords)]);

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
                <?php TradeHelper::itemBreadcrumbs($trade) ?>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <h1><?= Html::encode($trade->getTitle()) ?></h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="">
                    <a class="fancybox" href="<?= $trade->getMainPhotoUrl('max') ?>" data-fancybox-group="gallery">
                        <img src="<?= $trade->getMainPhotoUrl('big') ?>" alt="Заголовок" class="img-responsive">
                    </a>
                </div>
                <div class="kt-item-thumb">
                    <?php foreach ($trade->photos as $photo): ?>
                        <?php if ($photo->id == $trade->main_photo_id) {
                            continue;
                        } ?>
                        <a class="fancybox" href="<?= $photo->getUrl('max') ?>" data-fancybox-group="gallery">
                            <img src="<?= $photo->getUrl() ?>" alt="<?= $trade->getTitle() ?>" class="img-responsive">
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="kt-item-block">
                    <ul class="kt-item-listinfo">
                        <li><div class="kt-item-info">Артикул: <span class="kt-item-articul"><?= Html::encode($trade->code) ?></span></div></li>
                        <li><div class="kt-item-info">Статус: <span class="kt-item-status"><?= $trade->getStockString() ?></span></div></li>
                        <li><div class="kt-item-info">Цена: <span class="kt-item-price"><?= $trade->getPriceString() ?>/<?= $trade->getUomString() ?></span></div></li>
                    </ul>

                    <form id="kt-form-add-cart" class="form-inline" action="#" method="get">
                        <div class="input-group spinner">
                            <input type="text" class="form-control" value="1" min="0" max="9999999">
                            <div class="input-group-btn-vertical">
                                <button class="btn btn-default" type="button"><i class="fa fa-caret-up"></i></button>
                                <button class="btn btn-default" type="button"><i class="fa fa-caret-down"></i></button>
                            </div>
                        </div>
                        <script>
                            $(function(){
                                $('.spinner .btn:first-of-type').on('click', function() {
                                    var btn = $(this);
                                    var input = btn.closest('.spinner').find('input');
                                    if (input.attr('max') == undefined || parseInt(input.val()) < parseInt(input.attr('max'))) {
                                        input.val(parseInt(input.val(), 10) + 1);
                                    } else {
                                        btn.next("disabled", true);
                                    }
                                });
                                $('.spinner .btn:last-of-type').on('click', function() {
                                    var btn = $(this);
                                    var input = btn.closest('.spinner').find('input');
                                    if (input.attr('min') == undefined || parseInt(input.val()) > parseInt(input.attr('min'))) {
                                        input.val(parseInt(input.val(), 10) - 1);
                                    } else {
                                        btn.prev("disabled", true);
                                    }
                                });
                            });
                        </script>
                        <!-- <input class="form-control" placeholder="1" value="" size="4" type="text" id="search-input"> -->
                        <button class="form-control btn kt-btn-cart" type="submit">Заказать</button>
                    </form>

                    <?php if ($trade->canWholesales() && $wholeSales = $trade->getWholesales()): ?>
                    <div class="kt-item-infoset">Опт:</div>
                    <ul>
                        <?php foreach ($wholeSales as $wholeSale): ?>
                            <li>от <?= $wholeSale['from'] . ' ' . $trade->getUomString() ?> - <?= PriceHelper::normalize($wholeSale['price']) . ' ' . $trade->getCurrencyString() ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                    <?php if ($trade->user->company->site): ?>
                        <a href="<?= Url::to(['/site/outsite', 'url' => $trade->user->company->site]) ?>">Товар на сайте продавца</a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-md-4 col-sm-4 col-xs-12 kt-item-vendor-block">
                <div class="kt-item-vendor-text">
                    <ul>
                        <li><a href="<?= $trade->user->company->getUrl() ?>"><?= $trade->user->company->getFullName() ?></a></li>
                        <li><span class="kt-item-infoset">Телефон:</span> <?= $trade->user->company->getPhones(true) ?></li>
                        <li><span class="kt-item-infoset">Факс:</span> <?= Html::encode($trade->user->company->fax) ?></li>
                        <li><span class="kt-item-infoset">Адрес:</span> <?= Html::encode($trade->user->company->address) ?></li>
                        <li><span class="kt-item-infoset">Сайт:</span> <a href="<?= Url::to(['/site/outsite', 'url' => $trade->user->company->site]) ?>"><?= Html::encode($trade->user->company->site) ?></a></li>
                    </ul>
                    <div>
                        <a href="#ModalMsg" class="form-control btn kk-btn-contact" data-toggle="modal">
                            <span class="glyphicon glyphicon-envelope btn-xs"></span> Написать сообщение
                        </a>
                    </div>

                    <!--modal message-->
                    <div id="ModalMsg" class="modal fade">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    <h4>Cообщение для ООО "Новая объединенная компания 2017"</h4>
                                </div>

                                <div class="modal-body">
                                    <form>
                                        <div class="form-group">

                                            <b>Тема:</b> "Дубль заголовка товара"
                                        </div>
                                        <div class="form-group">

                                            <textarea rows="3" class="form-control" placeholder="Текст сообщения*"></textarea>
                                        </div>
                                        <div class="form-inline">
                                            <div class="form-group">
                                                <label class="sr-only" for="inputName">Имя</label>
                                                <input type="name" class="form-control" id="inputName" placeholder="Имя*">
                                            </div>
                                            <div class="form-group">
                                                <label class="sr-only" for="inputEmail">Email</label>
                                                <input type="email" class="form-control" id="inputEmail" placeholder="Email*">
                                            </div>
                                            <div class="form-group">
                                                <label class="sr-only" for="inputPhone">Телефон:</label>
                                                <input type="phone" class="form-control" id="inputPhone" placeholder="Телефон">
                                            </div>

                                        </div>
                                        <br />
                                        <div class="form-group">
                                            <label class="control-label col-md-3"><img src="/img/captcha.png" alt="" title="Обновить картинку" style="cursor:pointer"></label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" placeholder="Введите код с картинки*">
                                            </div>
                                        </div>
                                        <br />

                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary">Отправить сообщение</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- // modal message-->

                    <div class="kt-item-infoset">Доставка</div>
                    <div class="kt-item-delivery">
                        <?php foreach ($trade->user->company->deliveries as $companyDelivery): ?>
                        <input type="checkbox" id="hd-<?= $companyDelivery->id ?>" class="hide">
                        - <label for="hd-<?= $companyDelivery->id ?>"><?= $companyDelivery->delivery->name ?></label>
                        <div>
                            <?= nl2br(Html::encode($companyDelivery->terms)) ?>
                        </div>
                        <br />
                        <?php endforeach; ?>
                    </div>
                    <br>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h4>Описание товара</h4>
                <?= TextHelper::out($trade->description) ?>

                <div class="kt-item-vendgoods">Все товары продавца в <a href="<?= Url::to(['/trade/trade/list', 'category' => $trade->category->slug]) ?>"><?= $trade->category->name ?></a> (<?= $trade::find()->countInCategoryForUser($trade->category_id, $trade->user_id) ?>)</div>
                <div class="kt-notice-price">
                    Указанная цена на Комплекс изделий для проведения вертикального подводного вытяжения отделов позвоночника КИВ ПВП - "ТММ", носит ознакомительный характер и не является публичной офертой, определяемой положениями Статьи 437 (2) ГК РФ. Для уточнения цены, отправте запрос продавцу.
                    <br><br>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="md-12 col-sm-12 col-xs-12">
                <div class="sidebar-title">Похожие товары из <a href="<?= Url::to(['/trade/trade/list', 'category' => $trade->category->slug]) ?>"><?= $trade->category->name ?></a> (Block 2)</div>
                <div class="brand-baner">
                    <div class="brand-baner__slider owl-carousel-2  owl-carousel">
                        <div class="brand-baner__item">
                            <div class="content-block-caorusel-img"><a href="#"><img src="/img/418.jpg" alt="" align="center" class="img-responsive"></a></div>
                            <div class="text-col"><a href="#">Ферментер лабораторный 3 шт. с доп. оборудованием</a></div>
                            <div class="price-col">1 250 000 руб./шт.</div>
                            <div class="desc-col">Нить синяя полигликолидная</div>
                        </div>

                        <div class="brand-baner__item">
                            <div class="content-block-caorusel-img"><a href="#"><img src="/img/417.jpg" alt="" align="center" class="img-responsive"></a></div>
                            <div class="text-col"><a href="#">Нить синяя полигликолидная с капролактоном (75:25) рассасывающая...</a></div>
                            <div class="price-col">1 250 000 руб./шт.</div>
                            <div class="desc-col">Нить синяя полигликолидная</div>
                        </div>

                        <div class="brand-baner__item">
                            <div class="content-block-caorusel-img"><a href="#"><img src="/img/417.jpg" alt="" align="center" class="img-responsive"></a></div>
                            <div class="text-col"><a href="#">Нить синяя полигликолидная с капролактоном (75:25) рассасывающая...</a></div>
                            <div class="price-col">1 250 000 руб./шт.</div>
                            <div class="desc-col">Нить синяя полигликолидная</div>
                        </div>

                        <div class="brand-baner__item">
                            <div class="content-block-caorusel-img"><a href="#"><img src="/img/417.jpg" alt="" align="center" class="img-responsive"></a></div>
                            <div class="text-col"><a href="#">Нить синяя полигликолидная с капролактоном (75:25) рассасывающая...</a></div>
                            <div class="price-col">1 250 000 руб./шт.</div>
                            <div class="desc-col">Нить синяя полигликолидная</div>
                        </div>

                        <div class="brand-baner__item">
                            <div class="content-block-caorusel-img"><a href="#"><img src="/img/417.jpg" alt="" align="center" class="img-responsive"></a></div>
                            <div class="text-col"><a href="#">Нить синяя полигликолидная с капролактоном (75:25) рассасывающая...</a></div>
                            <div class="price-col">1 250 000 руб./шт.</div>
                            <div class="desc-col">Нить синяя полигликолидная</div>
                        </div>

                        <div class="brand-baner__item">
                            <div class="content-block-caorusel-img"><a href="#"><img src="/img/417.jpg" alt="" align="center" class="img-responsive"></a></div>
                            <div class="text-col"><a href="#">Нить синяя полигликолидная с капролактоном (75:25) рассасывающая...</a></div>
                            <div class="price-col">1 250 000 руб./шт.</div>
                            <div class="desc-col">Нить синяя полигликолидная</div>
                        </div>

                        <div class="brand-baner__item">
                            <div class="content-block-caorusel-img"><a href="#"><img src="/img/417.jpg" alt="" align="center" class="img-responsive"></a></div>
                            <div class="text-col"><a href="#">Нить синяя полигликолидная с капролактоном (75:25) рассасывающая...</a></div>
                            <div class="price-col">1 250 000 руб./шт.</div>
                            <div class="desc-col">Нить синяя полигликолидная</div>
                        </div>

                        <div class="brand-baner__item">
                            <div class="content-block-caorusel-img"><a href="#"><img src="/img/417.jpg" alt="" align="center" class="img-responsive"></a></div>
                            <div class="text-col"><a href="#">Нить синяя полигликолидная с капролактоном (75:25) рассасывающая...</a></div>
                            <div class="price-col">1 250 000 руб./шт.</div>
                            <div class="desc-col">Нить синяя полигликолидная</div>
                        </div>

                        <div class="brand-baner__item">
                            <div class="content-block-caorusel-img"><a href="#"><img src="/img/417.jpg" alt="" align="center" class="img-responsive"></a></div>
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
