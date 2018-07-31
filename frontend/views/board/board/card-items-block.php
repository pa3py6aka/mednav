<?php

use yii\helpers\Html;
use core\helpers\BoardHelper;
use core\components\ContextBlock;

/* @var $provider \yii\data\ActiveDataProvider */
/* @var $geo \core\entities\Geo|null */
/* @var $inCompany bool */

/* @var $board \core\entities\Board\Board */

$inCompany = isset($inCompany) ?: false;

?>
<?php if (!count($provider->models)): ?>
    <div class="list-item">
        <div class="row">
            <div class="col-xs-12" style="text-align:center;"><h3>По данному запросу объявлений не найдено</h3></div>
        </div>
    </div>
<?php else: ?>
    <?php foreach ($provider->models as $board): ?>
        <div class="list-item">
            <div class="row">
                <div class="col-md-2 col-sm-2 col-xs-12">
                    <a href="<?= $board->getUrl() ?>">
                        <img src="<?= $board->getMainPhotoUrl('small') ?>" alt="<?= Html::encode($board->name) ?>" class="img-responsive">
                    </a>
                </div>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <div class="text-col2">
                        <span class="do-item-bs"><?= $board->getDefaultType() ?></span>
                        <a href="<?= $board->getUrl() ?>"><?= Html::encode($board->name) ?></a>
                    </div>
                    <div class="desc-col"><?= Html::encode($board->note) ?></div>
                    <div class="list-vendor-info">
                        <i class="glyphicon glyphicon-calendar btn-xs city-icon-grey"></i>
                        <?= Yii::$app->formatter->asDate($board->updated_at) ?> /
                        <?php if (!$inCompany): ?>
                        <a href="<?= $board->author->getUrl() ?>"><?= $board->author->getVisibleName() ?></a> /
                        <?= $board->geo->name ?> /
                        <?php endif; ?>
                        <?= BoardHelper::contextCategoryLink($board) ?>
                    </div>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-12">
                    <div class="price-col"><?= $board->getPriceString() ?></div>
                </div>
            </div>
        </div>
        <?php ContextBlock::afterRow($provider->pagination->page, $provider->pagination->pageSize) ?>
    <?php endforeach; ?>
<?php endif; ?>
