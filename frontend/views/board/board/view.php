<?php
use yii\helpers\Url;
use yii\helpers\Html;
use core\helpers\BoardHelper;
use frontend\widgets\message\MessageWidget;

/* @var $this \yii\web\View */
/* @var $board \core\entities\Board\Board */

$this->title = $board->getTitle();
$this->registerMetaTag(['name' => 'description', 'content' => Html::encode($board->description)]);
$this->registerMetaTag(['name' => 'keywords', 'content' => Html::encode($board->keywords)]);

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
                        if ($category->isRoot()) {
                            continue;
                        }
                        ?><li><a href="<?= BoardHelper::categoryUrl($category, Yii::$app->session->get('geo', 'all')) ?>"><?= $category->getTitle() ?></a></li><?php
                    } ?>
                    <li><a href="<?= BoardHelper::categoryUrl($board->category, Yii::$app->session->get('geo', 'all')) ?>"><?= $board->category->getTitle() ?></a></li>
                </ul>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <h1>
                    <span class="title-param"><?= $board->defaultType ?></span>
                    <?= Html::encode($board->name) ?>
                </h1>
            </div>
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
                    <br>
                    <div class="alert alert-warning">
                        Владелец данного объявления его давно не обновлял, возможно, оно потеряло актуальность.
                    </div>
                <?php endif; ?>
                <div class="do-item-vendor-block">
                    <div class="do-item-vendor-text">
                        <ul>
                            <li><span class="kt-item-infoset">Продавец:</span> <a href="<?= $board->author->getUrl() ?>"><?= $board->author->getVisibleName() ?></a></li>
                            <?php if ($board->author->phone): ?><li><span class="kt-item-infoset">Телефон:</span> <?= Html::encode($board->author->phone) ?></li><?php endif; ?>
                            <?php if ($board->author->isCompany() && $board->author->isCompanyActive()): ?><li><span class="kt-item-infoset">Адрес:</span> <?= Html::encode($board->author->company->address) ?></li><?php endif; ?>
                            <?php if ($board->author->site): ?><li><span class="kt-item-infoset">Сайт:</span> <a href="<?= Url::to(['/site/outsite', 'url' => $board->author->site]) ?>"><?= $board->author->site ?></a></li><?php endif; ?>
                        </ul>
                        <div>
                            <?= MessageWidget::widget([
                                'toUser' => $board->author,
                                'subject' => $board->name,
                                'btnClass' => 'kt-btn-cart',
                                'btnWidth' => 'auto'
                            ]) ?>
                            <!--<a href="#ModalMsg" class="form-control btn kt-btn-cart" data-toggle="modal"><span class="glyphicon glyphicon-envelope btn-xs"></span> Написать сообщение</a>-->
                        </div>
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
