<?php

use yii\helpers\Html;
use core\helpers\ExpoHelper;

/* @var $provider \yii\data\ActiveDataProvider */

/* @var $expo \core\entities\Expo\Expo */

?>
<?php if (!count($provider->models)): ?>
    <div class="list-item">
        <div class="row">
            <div class="col-xs-12" style="text-align:center;"><h3>Ни одной выставки не найдено</h3></div>
        </div>
    </div>
<?php else: ?>
    <?php foreach ($provider->models as $expo): ?>
        <div class="list-item">
            <div class="row">
                <div class="col-md-2 col-sm-2 col-xs-12">
                    <a href="<?= $expo->getUrl() ?>">
                        <img src="<?= $expo->getMainPhotoUrl() ?>" alt="<?= $expo->getTitle() ?>" class="img-responsive">
                    </a>
                </div>
                <div class="col-md-10 col-sm-10 col-xs-12">
                    <div class="text-col2">
                        <a href="<?= $expo->getUrl() ?>"><?= $expo->getTitle() ?></a>
                    </div>
                    <div class="desc-col"><?= Html::encode(\yii\helpers\StringHelper::truncate($expo->intro, 100)) ?></div>
                    <div class="list-vendor-info">
                        <i class="glyphicon glyphicon-calendar btn-xs city-icon-grey" aria-hidden="true"></i><?= date('d-m-Y', $expo->created_at) ?>
                        / <?= ExpoHelper::contextCategoryLink($expo) ?>
                        / <i class="glyphicon glyphicon-eye-open btn-xs city-icon-grey" aria-hidden="true"></i><?= $expo->views ?>
                        / <i class="glyphicon glyphicon-map-marker btn-xs city-icon-grey"></i><?= $expo->getCity() . ($expo->city && $expo->show_dates ? ", " : "") ?>
                        <?php if ($expo->show_dates): ?>
                            <i class="glyphicon glyphicon-calendar btn-xs city-icon-grey"></i> <?= $expo->getCalendar() ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
