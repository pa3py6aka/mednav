<?php

use yii\helpers\Html;
use core\helpers\CompanyHelper;
use core\helpers\TextHelper;
use frontend\widgets\message\MessageWidget;

/* @var $this \yii\web\View */
/* @var $company \core\entities\Company\Company */
/* @var $page string */

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
                <?php CompanyHelper::companyBreadcrumbs($company, $page) ?>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12"><h1><?= $company->getFullName() ?></h1></div>
        </div>

        <div class="row">
            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="">
                    <!--на остальных страницах, ссылка ведет на страницу "О компании"-->
                    <a class="fancybox" href="<?= $company->getUrl() ?>" data-fancybox-group="gallery">
                        <img src="<?= $company->getLogoUrl() ?>" alt="" class="img-responsive">
                    </a>
                </div>
                <div class="kk-btn">
                    <?= MessageWidget::widget([
                        'toUser' => $company->user,
                        'subjectType' => MessageWidget::SUBJECT_TYPE_INPUT,
                        'btnClass' => 'kt-btn-cart',
                    ]) ?>
                    <a href="#ModalContact" class="form-control btn kk-btn-contact" data-toggle="modal">
                        <span class="glyphicon glyphicon-user btn-xs"></span> Добавить в контакты
                    </a>

                    <!-- modal add contact-->
                    <div id="ModalContact" class="modal fade">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    <h4>Уведомление</h4>
                                </div>
                                <div class="modal-body">
                                    Компания <b><?= $company->getFullName() ?></b> добавлена в ваш список контактов.
                                    <!--Уведомление, что данная компания уже в списке контактов "Компания <b>ООО "Название компании"</b> уже находится в вашем списке контактов." -->
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--// modal add contact-->
                </div>
            </div>
            <div class="col-md-8 col-sm-8 col-xs-12">
                <div class="kk-menu">
                    <?= $this->render('_menu', ['company' => $company]) ?>
                </div>
                <div class="kk-content">
                    <?php if ($page == 'main'): ?>
                        <?= TextHelper::out($company->description) ?>

                        <div class="kk-gallery">
                            <?php foreach ($company->photos as $photo): ?>
                                <a class="fancybox" href="<?= $photo->getUrl('max') ?>" data-fancybox-group="gallery">
                                    <img src="<?= $photo->getUrl() ?>" alt="" class="img-responsive">
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <?= $this->render('_' . $page, ['company' => $company]) ?>
                    <?php endif; ?>
                    <div class="clearfix"></div>
                </div>

                <div class="id-page">
                    <span class="glyphicon glyphicon-eye-open btn-xs"></span>
                    <?= $company->views ?>, на сайте с <?= Yii::$app->formatter->asDate($company->created_at) ?>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="md-12 col-sm-12 col-xs-12">
                <div class="sidebar-title">Похожие товары из <a href="#">Название раздела</a> (Block 2)</div>
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
