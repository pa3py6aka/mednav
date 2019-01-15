<?php

use yii\helpers\Html;
use core\helpers\BrandHelper;
use core\helpers\TextHelper;
use core\helpers\HtmlHelper;
use frontend\widgets\ContentBlock\ShowContentBlock;
use core\entities\ContentBlock;
use core\helpers\ArticleHelper;

/* @var $this \yii\web\View */
/* @var $brand \core\entities\Brand\Brand */

$this->title = Html::encode($brand->title ?: $brand->getTitle());
$this->registerMetaTag(['name' => 'description', 'content' => $brand->getMetaDescription()]);
$this->registerMetaTag(['name' => 'keywords', 'content' => $brand->getTagsString() ?: Html::encode($brand->meta_keywords)]);

?>
<div class="row">
    <div class="col-md-9 col-sm-9 col-xs-12">

        <?= ShowContentBlock::widget([
            'module' => ContentBlock::MODULE_BRAND,
            'place' => ContentBlock::PLACE_MAIN,
            'page' => ContentBlock::PAGE_VIEW,
            'category' => $brand->category,
            'count' => 1,
            'entity' => $brand,
        ]) ?>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <?php BrandHelper::itemBreadcrumbs($brand) ?>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <h1><?= Html::encode($brand->getTitle()) ?></h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3 col-sm-4 col-xs-12">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="art-item-img">
                            <a class="fancybox" href="<?= $brand->getMainPhotoUrl('max') ?>" data-fancybox-group="gallery">
                                <img src="<?= $brand->getMainPhotoUrl('big') ?>"<?= HtmlHelper::altForMainImage($brand->hasMainPhoto(), $brand->name) ?> class="img-responsive">
                            </a>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="kt-item-thumb">
                            <?php foreach ($brand->photos as $photo): ?>
                                <?php if ($photo->id == $brand->main_photo_id) {
                                    continue;
                                } ?>
                                <a class="fancybox" href="<?= $photo->getUrl('max') ?>" data-fancybox-group="gallery">
                                    <img src="<?= $photo->getUrl() ?>" alt="<?= $brand->getTitle() ?>" class="img-responsive" style="width:60px;">
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9 col-sm-8 col-xs-12">
                <div class="kk-content">
                    <?= TextHelper::out($brand->full_text, 'brand', true, (bool) $brand->indirect_links) ?>
                    <div>
                        Опубликовано: <?= ArticleHelper::authorString($brand) ?>
                        <i class="glyphicon glyphicon-calendar btn-xs city-icon-grey"></i><?= date('d-m-Y', $brand->created_at) ?>
                    </div>
                    <div class="clearfix"></div>
                    <div class="id-page"><span class="glyphicon glyphicon-eye-open btn-xs"></span><?= $brand->views ?></div>
                </div>
            </div>
        </div>

        <?= ShowContentBlock::widget([
            'module' => ContentBlock::MODULE_BRAND,
            'place' => ContentBlock::PLACE_MAIN,
            'page' => ContentBlock::PAGE_VIEW,
            'category' => $brand->category,
            'start' => 2,
            'entity' => $brand,
        ]) ?>
    </div>

    <!-- right col -->
    <div class="col-md-3 col-sm-3 hidden-xs">
        <div id="rightCol">
            <div style="margin: 10px 0;">
                <img src="/img/234.png" class="img-responsive" alt="">
            </div>

            <?= ShowContentBlock::widget([
                'module' => ContentBlock::MODULE_BRAND,
                'place' => ContentBlock::PLACE_SIDEBAR_RIGHT,
                'page' => ContentBlock::PAGE_VIEW,
                'category' => $brand->category,
                'entity' => $brand,
            ]) ?>
        </div><!-- // right col -->
    </div>
</div>
