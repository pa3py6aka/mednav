<?php

use yii\helpers\Html;
use core\helpers\NewsHelper;
use core\helpers\HtmlHelper;

/* @var $provider \yii\data\ActiveDataProvider */

/* @var $news \core\entities\News\News */

?>
<?php if (!count($provider->models)): ?>
    <div class="list-item">
        <div class="row">
            <div class="col-xs-12" style="text-align:center;"><h3>Ни одной новости не найдено</h3></div>
        </div>
    </div>
<?php else: ?>
    <?php foreach ($provider->models as $news): ?>
        <div class="list-item">
            <div class="row">
                <div class="col-md-2 col-sm-2 col-xs-12">
                    <a href="<?= $news->getUrl() ?>">
                        <img src="<?= $news->getMainPhotoUrl() ?>"<?= HtmlHelper::altForMainImage($news->hasMainPhoto(), $news->name) ?> class="img-responsive">
                    </a>
                </div>
                <div class="col-md-10 col-sm-10 col-xs-12">
                    <div class="text-col2">
                        <a href="<?= $news->getUrl() ?>"><?= $news->getTitle() ?></a>
                    </div>
                    <div class="desc-col"><?= Html::encode(\yii\helpers\StringHelper::truncate($news->intro, 100)) ?></div>
                    <div class="list-vendor-info">
                        <i class="glyphicon glyphicon-calendar btn-xs city-icon-grey" aria-hidden="true"></i><?= date('d-m-Y', $news->created_at) ?>
                        / <?= NewsHelper::contextCategoryLink($news) ?>
                        / <i class="glyphicon glyphicon-eye-open btn-xs city-icon-grey" aria-hidden="true"></i><?= $news->views ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
