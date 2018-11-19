<?php

use yii\helpers\Html;
use core\helpers\TradeHelper;
use core\helpers\CompanyHelper;
use core\helpers\HtmlHelper;
use core\components\ContextBlock;

/* @var $provider \yii\data\ActiveDataProvider */
/* @var $geo \core\entities\Geo|null */
/* @var $inCompany bool */

/* @var $trade \core\entities\Trade\Trade */

?>
<?php if (!count($provider->models)): ?>
    <div class="list-item">
        <div class="row">
            <div class="col-xs-12" style="text-align:center;"><h3>По данному запросу товаров не найдено</h3></div>
        </div>
    </div>
<?php else: ?>
    <?php foreach ($provider->models as $trade): ?>
        <div class="list-item">
            <div class="row">
                <div class="col-md-2 col-sm-2 col-xs-12">
                    <a href="<?= $trade->getUrl() ?>">
                        <img src="<?= $trade->getMainPhotoUrl() ?>"<?= HtmlHelper::altForMainImage((bool) $trade->main_photo_id, $trade->name) ?> class="img-responsive">
                    </a>
                </div>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <div class="text-col2">
                        <a href="<?= $trade->getUrl() ?>"><?= $trade->getTitle() ?></a>
                    </div>
                    <div class="desc-col"><?= Html::encode($trade->note) ?></div>
                    <div class="list-vendor-info">
                        <?php if (!$inCompany): ?>
                        <a href="<?= $trade->user->company->getUrl() ?>"><?= $trade->user->company->getFullName() ?></a> /
                        <?php endif; ?>
                        <?= $trade->geo->name ?> /
                        <?= CompanyHelper::getDeliveriesString($trade->user->company) ?> /
                        <?= TradeHelper::contextCategoryLink($trade) ?>
                    </div>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-12">
                    <div class="price-col"><?= $trade->getPriceString(false) ?></div>
                    <div class="list-item-option"><?= $trade->price ? $trade->userCategory->uom->sign : '' ?></div>
                    <div class="list-item-option"><?= $trade->userCategory->wholesale && $trade->getWholesales() ? "Есть опт" : "" ?></div>
                </div>
            </div>
        </div>
        <?php ContextBlock::afterRow($provider->pagination->page, $provider->pagination->pageSize) ?>
    <?php endforeach; ?>
<?php endif; ?>
