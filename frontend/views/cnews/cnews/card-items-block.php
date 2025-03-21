<?php

use yii\helpers\Html;
use core\helpers\CNewsHelper;
use core\helpers\HtmlHelper;
use core\components\ContextBlock;

/* @var $provider \yii\data\ActiveDataProvider */

/* @var $news \core\entities\CNews\CNews */

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
                        <?= $news->company_id ? '/ <a href="'. $news->company->getUrl() .'">' . $news->company->getFullName() . '</a>' : '' ?>
                        / <?= CNewsHelper::contextCategoryLink($news) ?>
                        / <i class="glyphicon glyphicon-eye-open btn-xs city-icon-grey" aria-hidden="true"></i><?= $news->views ?>
                    </div>
                </div>
            </div>
        </div>
        <?php ContextBlock::afterRow($provider->pagination, $provider->totalCount) ?>
    <?php endforeach; ?>
<?php endif; ?>
