<?php

use yii\helpers\Html;
use core\helpers\HtmlHelper;
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
$this->registerMetaTag(['name' => 'description', 'content' => Html::encode($trade->meta_description ?: $trade->note)]);
$this->registerMetaTag(['name' => 'keywords', 'content' => Html::encode($trade->meta_keywords)]);

?>
<div class="row">
    <div class="col-md-9 col-sm-9 col-xs-12">

        <?= ShowContentBlock::widget([
            'module' => ContentBlock::MODULE_TRADE,
            'place' => ContentBlock::PLACE_MAIN,
            'page' => ContentBlock::PAGE_VIEW,
            'category' => $trade->category,
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
                        <img src="<?= $trade->getMainPhotoUrl('big') ?>"<?= HtmlHelper::altForMainImage((bool) $trade->main_photo_id, $trade->name) ?> class="img-responsive">
                    </a>
                </div>
                <div class="kt-item-thumb">
                    <?php foreach ($trade->photos as $photo): ?>
                        <?php if ($photo->id == $trade->main_photo_id) {
                            continue;
                        } ?>
                        <a class="fancybox" href="<?= $photo->getUrl('max') ?>" data-fancybox-group="gallery">
                            <img src="<?= $photo->getUrl() ?>" alt="<?= $trade->getTitle() ?>" class="img-responsive" width=”55” height=”55”>
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
                    <?php if ($trade->external_link): ?>
                        <a href="/trade/vendor?id=<?= $trade->id ?>" target="_blank">Товар на сайте продавца</a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-md-4 col-sm-4 col-xs-12 kt-item-vendor-block">
                <div class="kt-item-vendor-text">
                    <ul>
                        <li><a href="<?= $trade->company->getUrl() ?>"><?= $trade->company->getFullName() ?></a></li>
                        <?= HtmlHelper::infosetListItem('Телефон:', $trade->company->getPhones(true)) ?>
                        <?= HtmlHelper::infosetListItem('Факс:', Html::encode($trade->company->fax)) ?>
                        <?= HtmlHelper::infosetListItem('Адрес:', [$trade->company->geo->name, Html::encode($trade->company->address)]) ?>
                        <?= HtmlHelper::infosetListItem('Сайт:', '<a href="' . Url::to(['/trade/outsite', 'url' => $trade->company->id]) .'" target="_blank">'. Html::encode($trade->company->site) .'</a>', $trade->company->site) ?>
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

                <div class="kt-item-vendgoods">Все товары продавца в <a href="<?= $trade->company->getUrl('trades', false, ['category' => $trade->category_id])/*Url::to(['/trade/trade/list', 'category' => $trade->category->slug, 'region' => 'all'])*/ ?>"><?= $trade->category->getContextName() ?></a> (<?= $trade::find()->countInCategoryForCompany($trade->category_id, $trade->company_id) ?>)</div>
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
            'category' => $trade->category,
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
                'category' => $trade->category,
                'entity' => $trade,
            ]) ?>
        </div><!-- // right col -->
    </div>
</div>
