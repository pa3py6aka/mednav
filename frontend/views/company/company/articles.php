<?php

use yii\helpers\Html;
use core\helpers\CompanyHelper;
use core\components\Settings;

/* @var $this \yii\web\View */
/* @var $company \core\entities\Company\Company */
/* @var $provider \yii\data\ActiveDataProvider */
/* @var $category null|\core\entities\Article\ArticleCategory */

/* @var $article \core\entities\Article\Article */

$this->title = CompanyHelper::pageTitle(Yii::$app->settings->get(Settings::ARTICLE_NAME), $company);
$this->registerMetaTag(['name' => 'description', 'content' => Yii::$app->settings->get(Settings::ARTICLE_NAME) . ' компании ' . $company->getFullName()]);
$this->registerMetaTag(['name' => 'keywords', 'content' => Html::encode($company->form . ', ' . $company->name . ($company->geo ? ', ' . $company->geo->name : '') . ', ' . Yii::$app->settings->get(Settings::ARTICLE_NAME))]);

?>
<div class="row">
    <div class="col-md-9 col-sm-9 col-xs-12">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <?php CompanyHelper::companyBreadcrumbs($company, 'articles') ?>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <h1>Статьи <?= $company->getFullName() ?></h1>
            </div>
            <div class="col-md-12 col-sm-12 hidden-xs">
                <?= $this->render('_actions-top', ['company' => $company]) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="kk-menu">
                    <?= $this->render('_menu', ['company' => $company]) ?>
                </div>

                <div class="card-items-block">
                    <?= $this->render('@frontend/views/article/article/card-items-block', [
                        'provider' => $provider,
                        'inCompany' => true,
                    ]) ?>
                </div>

                <div class="list-pagination has-overlay">
                    <?php if ($provider->pagination->pageCount > $provider->pagination->page + 1): ?>
                        <br>
                        <p
                           id="list-btn-scroll"
                           class="btn btn-list"
                           data-url="<?= $provider->pagination->createUrl($provider->pagination->page + 1) ?>"
                        >Показать ещё</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
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
