<?php

use yii\helpers\Html;
use core\helpers\ArticleHelper;
use core\helpers\HtmlHelper;

/* @var $provider \yii\data\ActiveDataProvider */

/* @var $article \core\entities\Article\Article */

?>
<?php if (!count($provider->models)): ?>
    <div class="list-item">
        <div class="row">
            <div class="col-xs-12" style="text-align:center;"><h3>Ни одной статьи не найдено</h3></div>
        </div>
    </div>
<?php else: ?>
    <?php foreach ($provider->models as $article): ?>
        <div class="list-item">
            <div class="row">
                <div class="col-md-2 col-sm-2 col-xs-12">
                    <a href="<?= $article->getUrl() ?>">
                        <img src="<?= $article->getMainPhotoUrl() ?>"<?= HtmlHelper::altForMainImage($article->hasMainPhoto(), $article->name) ?> class="img-responsive">
                    </a>
                </div>
                <div class="col-md-10 col-sm-10 col-xs-12">
                    <div class="text-col2">
                        <a href="<?= $article->getUrl() ?>"><?= $article->getTitle() ?></a>
                    </div>
                    <div class="desc-col"><?= Html::encode(\yii\helpers\StringHelper::truncate($article->intro, 100)) ?></div>
                    <div class="list-vendor-info">
                        <i class="glyphicon glyphicon-calendar btn-xs city-icon-grey" aria-hidden="true"></i><?= date('d-m-Y', $article->created_at) ?>
                        <?= $article->company_id ? '/ <a href="'. $article->company->getUrl() .'">' . $article->company->getFullName() . '</a>' : '' ?>
                        / <?= ArticleHelper::contextCategoryLink($article) ?>
                        / <i class="glyphicon glyphicon-eye-open btn-xs city-icon-grey" aria-hidden="true"></i><?= $article->views ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
