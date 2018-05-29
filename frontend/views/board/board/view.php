<?php
use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $board \core\entities\Board\Board */

$this->title = $board->getTitle();

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
                <ul class="breadcrumb">
                    <li><a href="<?= Yii::$app->homeUrl ?>">Главная</a></li>
                    <?php foreach ($board->category->parents as $category) {
                        ?><li><a href="<?= Url::to(['/board/board/index', 'category' => $category->slug]) ?>"><?= $category->getTitle() ?></a></li><?php
                    } ?>
                    <li><a href="<?= Url::to(['/board/board/index', 'category' => $board->category->slug]) ?>"><?= $board->category->getTitle() ?></a></li>
                </ul>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12"><h1><?= Html::encode($board->name) ?></h1></div>
        </div>
        <div><i class="glyphicon glyphicon-calendar btn-xs city-icon-grey"></i> <?= Yii::$app->formatter->asDate($board->updated_at) ?></div>
        <div class="row">
            <div class="col-md-4 col-sm-4 col-xs-12">
                <div>
                    <a class="fancybox" href="<?= $board->getMainPhotoUrl('max') ?>" data-fancybox-group="gallery">
                        <img src="<?= $board->getMainPhotoUrl() ?>" alt="<?= Html::encode($board->name) ?>" class="img-responsive">
                    </a>
                </div>
                <div class="kt-item-thumb">
                    <?php foreach ($board->photos as $photo): ?>
                        <?php if ($photo->id == $board->main_photo_id) {
                            continue;
                        } ?>
                        <a class="fancybox" href="<?= $photo->getUrl('max') ?>" data-fancybox-group="gallery">
                            <img src="<?= $photo->getUrl() ?>" alt="<?= Html::encode($board->name) ?>" class="img-responsive">
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-md-8 col-sm-8 col-xs-12">
                <div class="kt-item-block">
                    <div class="do-item-vendor">
                        Цена: <span class="kt-item-price"><?= $board->getPriceString() ?></span>
                    </div>

                    <?= \core\helpers\TextHelper::out($board->full_text) ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <?php if (!$board->isActually()): ?>
                    <div class="alert alert-warning">
                        Владелец данного объявления его давно не обновлял, возможно, оно потеряло актуальность.
                    </div>
                <?php endif; ?>
                <div class="do-item-vendor-block">
                    <div class="do-item-vendor-text">
                        <ul>
                            <li><span class="kt-item-infoset">Продавец:</span> <a href="<?= $board->author->getUrl() ?>"><?= $board->author->getVisibleName() ?></a></li>
                            <li><span class="kt-item-infoset">Телефон:</span> <?= $board->author->phone ?></li>
                            <li><span class="kt-item-infoset">Адрес:</span> Москва, ул. Пушкина д. 11 офис 421</li>
                            <li><span class="kt-item-infoset">Сайт:</span> <a href="<?= Url::to(['/site/outsite', 'url' => $board->author->site]) ?>"><?= $board->author->site ?></a></li>
                        </ul>
                        <form class="form-inline">
                            <a href="#ModalMsg" class="form-control btn kt-btn-cart" data-toggle="modal"><span class="glyphicon glyphicon-envelope btn-xs"></span> Написать сообщение</a>
                        </form>

                        <!--modal message-->
                        <div id="ModalMsg" class="modal fade">
                            <div class="modal-dialog">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        <h4>Cообщение для ООО "Название компании" / Имя Фамилия пользователя</h4>
                                    </div>

                                    <div class="modal-body">
                                        <form>
                                            <div class="form-group">

                                                <b>Тема:</b> "Дубль заголовка объявления"
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
                    </div>
                </div>
                <div class="id-page">id: <?= $board->id ?>, просмотров: <?= $board->views ?></div>
            </div>

        </div>

        <div class="row">
            <div class="md-12 col-sm-12 col-xs-12">
                <div class="sidebar-title">Похожие объявления из <a href="<?= \core\helpers\BoardHelper::categoryUrl($board->category) ?>"><?= $board->category->name ?></a> (Block 2)</div>
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
