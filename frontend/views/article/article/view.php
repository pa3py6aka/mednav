<?php

use yii\helpers\Html;
use core\helpers\ArticleHelper;
use core\helpers\TextHelper;
use core\helpers\HtmlHelper;
use frontend\widgets\CompanyMenuWidget;
use frontend\widgets\ContentBlock\ShowContentBlock;
use core\entities\ContentBlock;

/* @var $this \yii\web\View */
/* @var $article \core\entities\Article\Article */

$this->title = Html::encode($article->title ?: $article->getTitle());
$this->registerMetaTag(['name' => 'description', 'content' => $article->getMetaDescription()]);
$this->registerMetaTag(['name' => 'keywords', 'content' => $article->getTagsString() ?: Html::encode($article->meta_keywords)]);

?>
<div class="row">
    <div class="col-md-9 col-sm-9 col-xs-12">

        <?= ShowContentBlock::widget([
            'module' => ContentBlock::MODULE_ARTICLE,
            'place' => ContentBlock::PLACE_MAIN,
            'page' => ContentBlock::PAGE_VIEW,
            'category' => $article->category,
            'count' => 1,
            'entity' => $article,
        ]) ?>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <?php ArticleHelper::itemBreadcrumbs($article) ?>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <h1><?= Html::encode($article->getTitle()) ?></h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3 col-sm-4 col-xs-12">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="art-item-img">
                            <a class="fancybox" href="<?= $article->getMainPhotoUrl('max') ?>" data-fancybox-group="gallery">
                                <img src="<?= $article->getMainPhotoUrl('big') ?>"<?= HtmlHelper::altForMainImage($article->hasMainPhoto(), $article->name) ?> class="img-responsive">
                            </a>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="kt-item-thumb">
                            <?php foreach ($article->photos as $photo): ?>
                                <?php if ($photo->id == $article->main_photo_id) {
                                    continue;
                                } ?>
                                <a class="fancybox" href="<?= $photo->getUrl('max') ?>" data-fancybox-group="gallery">
                                    <img src="<?= $photo->getUrl() ?>" alt="<?= $article->getTitle() ?>" class="img-responsive" style="width:60px;">
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9 col-sm-8 col-xs-12">
                <div class="kk-content">
                    <?= TextHelper::out($article->full_text, 'articles', true, (bool) $article->indirect_links) ?>
                    <div>
                        Опубликовано: <?= $article->company_id ? '<a href="'. $article->company->getUrl() .'">' . $article->company->getFullName() . '</a> / ' : '' ?><i class="glyphicon glyphicon-calendar btn-xs city-icon-grey"></i><?= date('d-m-Y', $article->created_at) ?>
                    </div>
                    <div class="clearfix"></div>
                    <div class="id-page"><span class="glyphicon glyphicon-eye-open btn-xs"></span><?= $article->views ?></div>

                    <?= $article->company_id ? CompanyMenuWidget::widget(['company' => $article->company]) : '' ?>
                </div>
            </div>
        </div>

        <?= ShowContentBlock::widget([
            'module' => ContentBlock::MODULE_ARTICLE,
            'place' => ContentBlock::PLACE_MAIN,
            'page' => ContentBlock::PAGE_VIEW,
            'category' => $article->category,
            'start' => 2,
            'entity' => $article,
        ]) ?>
    </div>

    <!-- right col -->
    <div class="col-md-3 col-sm-3 hidden-xs">
        <div id="rightCol">
            <div style="margin: 10px 0;">
                <img src="/img/234.png" class="img-responsive" alt="">
            </div>

            <?= ShowContentBlock::widget([
                'module' => ContentBlock::MODULE_ARTICLE,
                'place' => ContentBlock::PLACE_SIDEBAR_RIGHT,
                'page' => ContentBlock::PAGE_VIEW,
                'category' => $article->category,
                'entity' => $article,
            ]) ?>
        </div><!-- // right col -->
    </div>
</div>
