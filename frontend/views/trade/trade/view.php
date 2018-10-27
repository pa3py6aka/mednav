<?php

use yii\helpers\Html;
use core\helpers\TradeHelper;
use core\helpers\TextHelper;
use core\helpers\PriceHelper;
use yii\helpers\Url;
use core\components\Cart\widgets\OrderButtonWidget;
use frontend\widgets\message\MessageWidget;
use frontend\widgets\ContentBlock\ShowContentBlock;
use core\entities\ContentBlock;

/* @var $this \yii\web\View */
/* @var $trade \core\entities\Trade\Trade */

$this->title = Html::encode($trade->meta_title ?: $trade->getTitle());
$this->registerMetaTag(['name' => 'description', 'content' => Html::encode($trade->description)]);
$this->registerMetaTag(['name' => 'keywords', 'content' => Html::encode($trade->meta_keywords)]);

?>
<div class="row">
    <div class="col-md-9 col-sm-9 col-xs-12">

        <?= ShowContentBlock::widget([
            'module' => ContentBlock::MODULE_TRADE,
            'place' => ContentBlock::PLACE_MAIN,
            'page' => ContentBlock::PAGE_VIEW,
            'count' => 1,
            'entity' => $trade,
        ]) ?>

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
                        <li><div class="kt-item-info">Цена: <span class="kt-item-price"><?= $trade->getFullPriceString() ?></span></div></li>
                    </ul>

                    <?= OrderButtonWidget::widget(['productId' => $trade->id]) ?>

                    <?php if ($trade->canWholesales() && $wholeSales = $trade->getWholesales()): ?>
                    <div class="kt-item-infoset">Опт:</div>
                    <ul>
                        <?php foreach ($wholeSales as $wholeSale): ?>
                            <li>от <?= $wholeSale['from'] . ' ' . $trade->getUomString() ?> - <?= PriceHelper::normalize($wholeSale['price']) . ' ' . $trade->getCurrencyString() ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                    <?php if ($trade->company->site): ?>
                        <a href="<?= Url::to(['/site/outsite', 'url' => $trade->company->site]) ?>">Товар на сайте продавца</a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-md-4 col-sm-4 col-xs-12 kt-item-vendor-block">
                <div class="kt-item-vendor-text">
                    <ul>
                        <li><a href="<?= $trade->company->getUrl() ?>"><?= $trade->company->getFullName() ?></a></li>
                        <li><span class="kt-item-infoset">Телефон:</span> <?= $trade->company->getPhones(true) ?></li>
                        <li><span class="kt-item-infoset">Факс:</span> <?= Html::encode($trade->company->fax) ?></li>
                        <li><span class="kt-item-infoset">Адрес:</span> <?= Html::encode($trade->company->address) ?></li>
                        <li><span class="kt-item-infoset">Сайт:</span> <a href="<?= Url::to(['/site/outsite', 'url' => $trade->company->site]) ?>"><?= Html::encode($trade->company->site) ?></a></li>
                    </ul>
                    <div>
                        <?= MessageWidget::widget([
                            'toUser' => $trade->user,
                            'subject' => $trade->name,
                        ]) ?>
                    </div>

                    <div class="kt-item-infoset">Доставка</div>
                    <div class="kt-item-delivery">
                        <?php foreach ($trade->company->deliveries as $companyDelivery): ?>
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
                <?= TextHelper::out($trade->description, 'trade') ?>

                <div class="kt-item-vendgoods">Все товары продавца в <a href="<?= Url::to(['/trade/trade/list', 'category' => $trade->category->slug]) ?>"><?= $trade->category->name ?></a> (<?= $trade::find()->countInCategoryForCompany($trade->category_id, $trade->company_id) ?>)</div>
                <div class="kt-notice-price">
                    Указанная цена на Комплекс изделий для проведения вертикального подводного вытяжения отделов позвоночника КИВ ПВП - "ТММ", носит ознакомительный характер и не является публичной офертой, определяемой положениями Статьи 437 (2) ГК РФ. Для уточнения цены, отправте запрос продавцу.
                    <br><br>
                </div>
            </div>
        </div>

        <?= ShowContentBlock::widget([
            'module' => ContentBlock::MODULE_TRADE,
            'place' => ContentBlock::PLACE_MAIN,
            'page' => ContentBlock::PAGE_VIEW,
            'start' => 2,
            'entity' => $trade,
        ]) ?>
    </div>

    <!-- right col -->
    <div class="col-md-3 col-sm-3 hidden-xs">
        <div id="rightCol">
            <div style="margin: 10px 0;"><img src="/img/234.png" class="img-responsive" alt=""></div>

            <?= ShowContentBlock::widget([
                'module' => ContentBlock::MODULE_TRADE,
                'place' => ContentBlock::PLACE_SIDEBAR_RIGHT,
                'page' => ContentBlock::PAGE_VIEW,
                'entity' => $trade,
            ]) ?>
        </div><!-- // right col -->
    </div>
</div>
