<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use frontend\assets\AppAsset;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <title><?= Html::encode($this->title) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <?= Html::csrfMetaTags() ?>

    <?php $this->head() ?>

    <!--[if lt IE 9]>
    <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body>
<?php $this->beginBody() ?>

<div class="container-fluid">
    <div id="wrap">
        <!--block top-->
        <div class="row">
            <div class="col-md-6 col-lg-6 hidden-sm hidden-xs"><div id="bn-top" class="img-responsive">Banner 1</div></div>
            <div class="col-md-6 col-lg-6 hidden-sm hidden-xs"><div id="bn-top" class="img-responsive">Banner 2</div></div>
        </div>
        <!-- // block top-->
        <div class="row">
            <div class="col-md-2">
                <div id="logo">
                    <a href="/"><img src="/img/mednav-logo.png" alt="" class="img-responsive"></a>
                    <div id="logo-tx">Для закупщиков медоборудования</div>
                </div>
            </div>

            <div class="col-md-8">
                <!--search form-->
                <div class="fm-search">
                    <form id="form-search" class="form-inline" action="#" method="get">
                        <input class="form-control" placeholder="Поиск" value="" size="45" type="text" id="search-input">
                        <select class="form-control">
                            <option>Товары</option>
                            <option>Объявления</option>
                            <option>Новости</option>
                        </select>
                        <button class="btn btn-default" type="submit">Найти</button>
                    </form>
                </div>
                <!-- // search form-->
            </div>
            <!--cart block-->
            <div class="col-md-2 col-sm-3 col-xs-12">
                <div class="cart-block-box">
                    <a href="#" class="cart-block"><span class="glyphicon glyphicon-shopping-cart btn-xs"></span> Корзина <span class="badge">42</span></a>
                </div>
            </div>
            <!-- // cart block-->
        </div>

        <!--menu-->
        <div class="row">
            <div class="col-md-12">
                <div id="top-menu">
                    <nav id="navMenu" class="navbar-default navbar" role="navigation">
                        <div class="wrapp-menu">
                            <div class="navbar-header">
                                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navMenu-collapse">
                                    <span class="sr-only">Toggle navigation</span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                            </div>
                            <div id="navMenu-collapse" class="collapse navbar-collapse">
                                <ul id="w1" class="navbar-nav navbar-left nav"><li><a href="/trade">Каталог товаров</a></li>
                                    <li><a href="/board">Объявления</a></li>
                                    <li><a href="/company">Компании</a></li>
                                    <li><a href="/expo">Выставки</a></li>
                                    <li><a href="/brand">Бренды</a></li>
                                    <li><a href="/articles">Справочные материалы</a></li>

                                    <?php if (Yii::$app->user->isGuest): ?>
                                        <li><a href="<?= Url::to(['/auth/signup']) ?>">Регистрация</a></li>
                                        <li><a href="<?= Url::to(['/auth/login']) ?>">Вход</a></li>
                                    <?php else: ?>
                                        <li><a href="<?= Url::to(['/user/cabinet']) ?>"><?= Yii::$app->user->identity->username ?></a></li>
                                        <li><a href="<?= Url::to(['/auth/logout']) ?>" data-method="post">Выход</a></li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
        <!--// menu-->
    </div>

    <div class="scrollup hidden-sm hidden-xs">
        <i class="fa fa-chevron-up"></i>
    </div>

    <?= $content ?>

    <div class="row">
        <div class="col-md-12 footer-wrap">
            <div class="row">
                <div class="col-md-3 col-sm-4">
                    <div class="logo-footer"><a href="/"><img src="img/mednav-f.png" alt="B2b площадка для закупки медицинского оборудования, мед. мебели и расходных материалов."></a></div>
                    <div class="footer-about">B2B площадка для закупщиков<br /> и поставщиков медицинского оборудования,<br /> мебели и расходных материалов в России<br /><br />
                        &copy; 2017 Все права защищены.<br /></div>
                </div>
                <div class="col-md-3 col-sm-4 hidden-xs">
                    <div id="footer-lnk-block">
                        <ul class="footer-merker-list">
                            <li><a href="/trade" class="footer-lnk">Каталог медицинского оборудования</a></li>
                            <li><a href="/board" class="footer-lnk">Объявления о продаже медтехники</a></li>
                            <li><a href="/company" class="footer-lnk">Каталог поставщиков медтехники</a></li>
                            <li><a href="/register?footer-add-goods" title="Добавить свой товар и компанию!" class="footer-lnk-add">Добавить товар/компанию</a></li>
                        </ul></div>
                </div>
                <div class="col-md-3 hidden-sm hidden-xs">
                    <div id="footer-lnk-block">
                        <ul class="footer-merker-list">
                            <li><a href="/cnews" class="footer-lnk">Новости и пресс-релизы компаний</a></li>
                            <li><a href="/expo" class="footer-lnk">Отраслевые выставки</a></li>
                            <li><a href="/news" class="footer-lnk">Новости мед. отрасли</a></li>
                            <li><a href="/register?footer-add-content" title="Добавить новость, спецпредложение или пресс-релиз компании!" class="footer-lnk-add">Добавить пресс-релиз</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-3 col-sm-4">
                    <div id="footer-lnk-block">
                        <div><span class="glyphicon glyphicon-envelope btn-xs"></span><a href="/contact" class="footer-lnk">Написать нам</a></div>
                        <div><span class="glyphicon glyphicon-info-sign btn-xs"></span><a href="/about.html" class="footer-lnk">О проекте</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><!--/container-fluid-->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
