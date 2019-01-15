<?php

use yii\helpers\Html;
use core\helpers\ArticleHelper;
use core\helpers\ExpoHelper;
use core\helpers\HtmlHelper;
use core\helpers\TextHelper;
use frontend\widgets\ContentBlock\ShowContentBlock;
use core\entities\ContentBlock;

/* @var $this \yii\web\View */
/* @var $expo \core\entities\Expo\Expo */

$this->title = Html::encode($expo->title ?: $expo->getTitle());
$this->registerMetaTag(['name' => 'description', 'content' => $expo->getMetaDescription()]);
$this->registerMetaTag(['name' => 'keywords', 'content' => $expo->getTagsString() ?: Html::encode($expo->meta_keywords)]);

?>
<div class="row">
    <div class="col-md-9 col-sm-9 col-xs-12">

        <?= ShowContentBlock::widget([
            'module' => ContentBlock::MODULE_EXPO,
            'place' => ContentBlock::PLACE_MAIN,
            'page' => ContentBlock::PAGE_VIEW,
            'category' => $expo->category,
            'count' => 1,
            'entity' => $expo,
        ]) ?>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <?php ExpoHelper::itemBreadcrumbs($expo) ?>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <h1><?= Html::encode($expo->getTitle()) ?></h1>
                <i class="glyphicon glyphicon-map-marker btn-xs city-icon-grey"></i><?= $expo->getCity() . ($expo->city && $expo->show_dates ? ", " : "") ?>
                <?php if ($expo->show_dates): ?>
                    <i class="glyphicon glyphicon-calendar btn-xs city-icon-grey"></i> <?= $expo->getCalendar() ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3 col-sm-4 col-xs-12">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="art-item-img">
                            <a class="fancybox" href="<?= $expo->getMainPhotoUrl('max') ?>" data-fancybox-group="gallery">
                                <img src="<?= $expo->getMainPhotoUrl('big') ?>"<?= HtmlHelper::altForMainImage($expo->hasMainPhoto(), $expo->name) ?> class="img-responsive">
                            </a>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="kt-item-thumb">
                            <?php foreach ($expo->photos as $photo): ?>
                                <?php if ($photo->id == $expo->main_photo_id) {
                                    continue;
                                } ?>
                                <a class="fancybox" href="<?= $photo->getUrl('max') ?>" data-fancybox-group="gallery">
                                    <img src="<?= $photo->getUrl() ?>" alt="<?= $expo->getTitle() ?>" class="img-responsive" style="width:60px;">
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9 col-sm-8 col-xs-12">
                <div class="kk-content">
                    <?= TextHelper::out($expo->full_text, 'expo', true, (bool) $expo->indirect_links) ?>
                    <div>
                        Опубликовано: <?= ArticleHelper::authorString($expo) ?>
                        <i class="glyphicon glyphicon-calendar btn-xs city-icon-grey"></i><?= date('d-m-Y', $expo->created_at) ?>
                    </div>
                    <div class="clearfix"></div>
                    <div class="id-page"><span class="glyphicon glyphicon-eye-open btn-xs"></span><?= $expo->views ?></div>
                </div>
            </div>
        </div>

        <?= ShowContentBlock::widget([
            'module' => ContentBlock::MODULE_EXPO,
            'place' => ContentBlock::PLACE_MAIN,
            'page' => ContentBlock::PAGE_VIEW,
            'category' => $expo->category,
            'start' => 2,
            'entity' => $expo,
        ]) ?>
    </div>

    <!-- right col -->
    <div class="col-md-3 col-sm-3 hidden-xs">
        <div id="rightCol">
            <div style="margin: 10px 0;">
                <img src="/img/234.png" class="img-responsive" alt="">
            </div>

            <?= ShowContentBlock::widget([
                'module' => ContentBlock::MODULE_EXPO,
                'place' => ContentBlock::PLACE_SIDEBAR_RIGHT,
                'page' => ContentBlock::PAGE_VIEW,
                'category' => $expo->category,
                'entity' => $expo,
            ]) ?>
        </div><!-- // right col -->
    </div>
</div>
