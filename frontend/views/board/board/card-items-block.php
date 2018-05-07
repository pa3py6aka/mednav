<?php

use yii\helpers\Html;
use core\helpers\BoardHelper;

/* @var $provider \yii\data\ActiveDataProvider */
/* @var $geo \core\entities\Geo|null */

/* @var $board \core\entities\Board\Board */

?>
<?php foreach ($provider->models as $board): ?>
    <div class="list-item">
        <div class="row">
            <div class="col-md-2 col-sm-2 col-xs-12">
                <a href="<?= $board->getUrl() ?>">
                    <img src="<?= $board->mainPhoto ? $board->mainPhoto->getUrl() : '/img/100.png' ?>" alt="<?= $board->title ?>" class="img-responsive">
                </a>
            </div>
            <div class="col-md-8 col-sm-8 col-xs-12">
                <div class="text-col2">
                    <span class="do-item-bs"><?= $board->typeBoardParameter ? $board->typeBoardParameter->option->name : '' ?></span> <a href="<?= $board->getUrl() ?>"><?= Html::encode($board->name) ?></a>
                </div>
                <div class="desc-col"><?= Html::encode($board->note) ?></div>
                <div class="list-vendor-info"><i class="glyphicon glyphicon-calendar btn-xs city-icon-grey"></i> <?= Yii::$app->formatter->asDate($board->created_at) ?> / <a href="#">ООО Компания оптовых цен НВ-Лаб</a> / <?= $board->geo->name ?> / <a href="<?= BoardHelper::categoryUrl($board->category, $geo) ?>" class="list-lnk"><?= $board->category->name ?></a></div>
            </div>
            <div class="col-md-2 col-sm-2 col-xs-12"><div class="price-col"><?= $board->getPriceString() ?></div></div>
        </div>
    </div>
<?php endforeach; ?>
