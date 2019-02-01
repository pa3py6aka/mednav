<?php

use yii\helpers\Html;
use yii\helpers\StringHelper;
use core\components\ContextBlock;
use core\helpers\HtmlHelper;

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
                        <img src="<?= $company->getLogoUrl() ?>"<?= HtmlHelper::altForMainImage((bool) $company->logo, $company->getFullName()) ?> class="img-responsive">
                    </a>
                </div>
                <div class="col-md-10 col-sm-10 col-xs-12">
                    <div class="text-col2">
                        <a href="<?= $company->getUrl() ?>"><?= $company->getFullName() ?></a>
                    </div>
                    <div class="desc-col"><?= Html::encode(StringHelper::truncate($company->short_description, 100)) ?></div>
                    <div class="list-vendor-info">
                        <?= HtmlHelper::showIfIs('<a href="' . $company->getUrl('trades') . '">Товары</a> <sup>' . count($company->trades) . '</sup>', count($company->trades)) ?>
                        <?= HtmlHelper::showIfIs('<a href="' . $company->getUrl('boards') . '">Объявления</a> <sup>' . count($company->boards) . '</sup>', count($company->boards)) ?>
                        <?= HtmlHelper::showIfIs('<a href="' . $company->getUrl('cnews') . '">Новости</a> <sup>' . count($company->cNews) . '</sup>', count($company->cNews)) ?>
                        <?php //= HtmlHelper::showIfIs('<a href="' . $company->getUrl('articles') . '">Статьи</a> <sup>' . count($company->articles) . '</sup>', count($company->articles)) ?>
                        <span class="glyphicon glyphicon-map-marker btn-xs city-icon-grey"></span><?= $company->geo->name ?>
                    </div>
                </div>
            </div>
        </div>
        <?php ContextBlock::afterRow($provider->pagination, $provider->totalCount) ?>
    <?php endforeach; ?>
<?php endif; ?>
