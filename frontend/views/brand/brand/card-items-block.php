<?php

use yii\helpers\Html;
use core\helpers\BrandHelper;

/* @var $provider \yii\data\ActiveDataProvider */

/* @var $brand \core\entities\Brand\Brand */

?>
<?php if (!count($provider->models)): ?>
    <div class="list-item">
        <div class="row">
            <div class="col-xs-12" style="text-align:center;"><h3>Ни одного бренда не найдено</h3></div>
        </div>
    </div>
<?php else: ?>
    <?php foreach ($provider->models as $brand): ?>
        <div class="list-item">
            <div class="row">
                <div class="col-md-2 col-sm-2 col-xs-12">
                    <a href="<?= $brand->getUrl() ?>">
                        <img src="<?= $brand->getMainPhotoUrl() ?>" alt="<?= $brand->getTitle() ?>" class="img-responsive">
                    </a>
                </div>
                <div class="col-md-10 col-sm-10 col-xs-12">
                    <div class="text-col2">
                        <a href="<?= $brand->getUrl() ?>"><?= $brand->getTitle() ?></a>
                    </div>
                    <div class="desc-col"><?= Html::encode(\yii\helpers\StringHelper::truncate($brand->intro, 100)) ?></div>
                    <div class="list-vendor-info">
                        <i class="glyphicon glyphicon-calendar btn-xs city-icon-grey" aria-hidden="true"></i><?= date('d-m-Y', $brand->created_at) ?>
                        / <?= BrandHelper::contextCategoryLink($brand) ?>
                        / <i class="glyphicon glyphicon-eye-open btn-xs city-icon-grey" aria-hidden="true"></i><?= $brand->views ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
