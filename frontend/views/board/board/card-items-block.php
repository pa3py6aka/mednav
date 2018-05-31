<?php

use yii\helpers\Html;
use core\helpers\BoardHelper;

/* @var $provider \yii\data\ActiveDataProvider */
/* @var $geo \core\entities\Geo|null */

/* @var $board \core\entities\Board\Board */

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
                        <img src="<?= $board->getMainPhotoUrl('small') ?>" alt="<?= $board->getTitle() ?>" class="img-responsive">
                    </a>
                </div>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <div class="text-col2">
                        <span class="do-item-bs"><?= $board->typeBoardParameter ? $board->typeBoardParameter->option->name : '' ?></span> <a href="<?= $board->getUrl() ?>"><?= Html::encode($board->name) ?></a>
                    </div>
                    <div class="desc-col"><?= Html::encode($board->note) ?></div>
                    <div class="list-vendor-info"><i class="glyphicon glyphicon-calendar btn-xs city-icon-grey"></i> <?= Yii::$app->formatter->asDate($board->updated_at) ?> / <a href="#">ООО Компания оптовых цен НВ-Лаб</a> / <?= $board->geo->name ?> / <?= BoardHelper::contextCategoryLink($board) ?></div>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-12"><div class="price-col"><?= $board->getPriceString() ?></div></div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
