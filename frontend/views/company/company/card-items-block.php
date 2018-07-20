<?php
use yii\helpers\Html;
use yii\helpers\StringHelper;

/* @var $provider \yii\data\ActiveDataProvider */
/* @var $geo \core\entities\Geo|null */

/* @var $company \core\entities\Company\Company */

?>
<?php if (!count($provider->models)): ?>
    <div class="list-item">
        <div class="row">
            <div class="col-xs-12" style="text-align:center;"><h3>По данному запросу компаний не найдено</h3></div>
        </div>
    </div>
<?php else: ?>
    <?php foreach ($provider->models as $company): ?>
        <div class="list-item">
            <div class="row">
                <div class="col-md-2 col-sm-2 col-xs-12">
                    <a href="<?= $company->getUrl() ?>">
                        <img src="<?= $company->getMainPhotoUrl() ?>" alt="<?= $company->getTitle() ?>" class="img-responsive">
                    </a>
                </div>
                <div class="col-md-10 col-sm-10 col-xs-12">
                    <div class="text-col2">
                        <a href="<?= $company->getUrl() ?>"><?= $company->getFullName() ?></a>
                    </div>
                    <div class="desc-col"><?= Html::encode(StringHelper::truncate($company->short_description, 100)) ?></div>
                    <div class="list-vendor-info">
                        <a href="<?= $company->getUrl('trades') ?>">Товары</a> <sup><?= count($company->trades) ?></sup>
                        <a href="<?= $company->getUrl('boards') ?>">Объявления</a> <sup><?= count($company->boards) ?></sup>
                        <a href="#">Новости</a> <sup>120</sup>
                        <a href="#">Статьи</a> <sup>2</sup>
                        <span class="glyphicon glyphicon-map-marker btn-xs city-icon-grey"></span><?= $company->geo->name ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
