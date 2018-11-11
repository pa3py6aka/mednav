<?php

use yii\helpers\Html;
use core\helpers\CNewsHelper;
use core\helpers\TextHelper;
use frontend\widgets\CompanyMenuWidget;

/* @var $this \yii\web\View */
/* @var $news \core\entities\CNews\CNews */

$this->title = Html::encode($news->title ?: $news->getTitle());
$this->registerMetaTag(['name' => 'description', 'content' => $news->getMetaDescription()]);
$this->registerMetaTag(['name' => 'keywords', 'content' => $news->getTagsString() ?: Html::encode($news->meta_keywords)]);

?>
<div class="row">
    <div class="col-md-9 col-sm-9 col-xs-12">

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <?php CNewsHelper::itemBreadcrumbs($news) ?>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <h1><?= Html::encode($news->getTitle()) ?></h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3 col-sm-4 col-xs-12">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="art-item-img">
                            <a class="fancybox" href="<?= $news->getMainPhotoUrl('max') ?>" data-fancybox-group="gallery">
                                <img src="<?= $news->getMainPhotoUrl('big') ?>" alt="<?= $news->getTitle() ?>" class="img-responsive">
                            </a>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="kt-item-thumb">
                            <?php foreach ($news->photos as $photo): ?>
                                <?php if ($photo->id == $news->main_photo_id) {
                                    continue;
                                } ?>
                                <a class="fancybox" href="<?= $photo->getUrl('max') ?>" data-fancybox-group="gallery">
                                    <img src="<?= $photo->getUrl() ?>" alt="<?= $news->getTitle() ?>" class="img-responsive" style="width:60px;">
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9 col-sm-8 col-xs-12">
                <div class="kk-content">
                    <?= TextHelper::out($news->full_text, 'cnews', true, (bool) $news->indirect_links) ?>
                    <div>
                        Опубликовано: <?= $news->company_id ? '<a href="'. $news->company->getUrl() .'">' . $news->company->getFullName() . '</a> / ' : '' ?><i class="glyphicon glyphicon-calendar btn-xs city-icon-grey"></i><?= date('d-m-Y', $news->created_at) ?>
                    </div>
                    <div class="clearfix"></div>
                    <div class="id-page"><span class="glyphicon glyphicon-eye-open btn-xs"></span><?= $news->views ?></div>

                    <?= $news->company_id ? CompanyMenuWidget::widget(['company' => $news->company]) : '' ?>
                </div>
            </div>
        </div>
    </div>

    <!-- right col -->
    <div class="col-md-3 col-sm-3 hidden-xs">
        <div id="rightCol">

        </div><!-- // right col -->
    </div>
</div>
