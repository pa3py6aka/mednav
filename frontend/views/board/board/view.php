<?php
use yii\helpers\Url;
use yii\helpers\Html;
use core\helpers\BoardHelper;
use core\helpers\HtmlHelper;
use frontend\widgets\message\MessageWidget;
use frontend\widgets\ContentBlock\ShowContentBlock;
use core\entities\ContentBlock;

/* @var $this \yii\web\View */
/* @var $board \core\entities\Board\Board */

$this->title = $board->getTitle();
$this->registerMetaTag(['name' => 'description', 'content' => Html::encode($board->description)]);
$this->registerMetaTag(['name' => 'keywords', 'content' => Html::encode($board->keywords)]);

?>
<div class="row">
    <div class="col-md-9 col-sm-9 col-xs-12">

        <?= ShowContentBlock::widget([
            'module' => ContentBlock::MODULE_BOARD,
            'place' => ContentBlock::PLACE_MAIN,
            'page' => ContentBlock::PAGE_VIEW,
            'count' => 1,
            'entity' => $board,
        ]) ?>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <ul class="breadcrumb">
                    <li><a href="<?= Yii::$app->homeUrl ?>">Главная</a></li>
                    <li><a href="<?= Url::to(['/board/board/list', 'region' => Yii::$app->session->get('geo', 'all')]) ?>"><?= Yii::$app->settings->get(\core\components\Settings::BOARD_NAME) ?></a></li>
                    <?php foreach ($board->category->parents as $category) {
                        if ($category->isRoot()) {
                            continue;
                        }
                        ?><li><a href="<?= BoardHelper::categoryUrl($category, Yii::$app->session->get('geo', 'all')) ?>"><?= $category->name ?></a></li><?php
                    } ?>
                    <li><a href="<?= BoardHelper::categoryUrl($board->category, Yii::$app->session->get('geo', 'all')) ?>"><?= $board->category->name ?></a></li>
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
                        <img src="<?= $board->getMainPhotoUrl('big') ?>"<?= HtmlHelper::altForMainImage((bool) $board->main_photo_id, $board->name) ?> class="img-responsive">
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
                        <?php foreach ($board->boardParameters as $parameter): ?>
                            <div>
                                <?= $parameter->parameter->name ?>: <span><?= Html::encode($parameter->getValueByType(false, true)) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>


                    <?= \core\helpers\TextHelper::out($board->full_text, 'board') ?>
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
                            <?php if ($board->author->getPhone()): ?><li><span class="kt-item-infoset">Телефон:</span> <?= $board->author->getPhone() ?></li><?php endif; ?>
                            <?php if ($board->author->isCompany() && $board->author->isCompanyActive()): ?><li><span class="kt-item-infoset">Адрес:</span> <?= $board->author->company->geo->name . ', ' . Html::encode($board->author->company->address) ?></li><?php endif; ?>
                            <?= HtmlHelper::infosetListItem('Регоин:', $board->author->geo_id ? $board->author->geo->name : '', !$board->author->isCompany() && $board->author->geo_id) ?>
                            <?php if ($board->author->getSite()): ?><li><span class="kt-item-infoset">Сайт:</span> <a href="<?= Url::to(['/board/board/outsite', 'id' => $board->id]) ?>" target="_blank"><?= Html::encode($board->author->getSite()) ?></a></li><?php endif; ?>
                            <?= HtmlHelper::infosetListItem('Skype:', $board->author->skype, !$board->author->isCompany() && $board->author->skype) ?>
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

        <?= ShowContentBlock::widget([
            'module' => ContentBlock::MODULE_BOARD,
            'place' => ContentBlock::PLACE_MAIN,
            'page' => ContentBlock::PAGE_VIEW,
            'start' => 2,
            'entity' => $board,
        ]) ?>

    </div>

    <div class="col-md-3 col-sm-3 hidden-xs">
        <!-- right col -->
        <div id="rightCol">
            <!-- Banner ДО-Контент -->
            <div style="margin: 10px 0;"><img src="/img/234.png" class="img-responsive" alt=""></div>
            <!-- // Banner ДО-Контент -->

            <?= ShowContentBlock::widget([
                'module' => ContentBlock::MODULE_BOARD,
                'place' => ContentBlock::PLACE_SIDEBAR_RIGHT,
                'page' => ContentBlock::PAGE_VIEW,
                'entity' => $board,
            ]) ?>
        </div>
        <!-- // right col -->
    </div>
</div>
