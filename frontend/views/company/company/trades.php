<?php
use yii\helpers\Html;
use core\helpers\CompanyHelper;
use core\components\Settings;
use frontend\widgets\ContentBlock\ShowContentBlock;
use core\entities\ContentBlock;

/* @var $this \yii\web\View */
/* @var $company \core\entities\Company\Company */
/* @var $provider \yii\data\ActiveDataProvider */
/* @var $category null|\core\entities\Trade\TradeCategory */

/* @var $trade \core\entities\Trade\Trade */

$this->title = CompanyHelper::pageTitle(Yii::$app->settings->get(Settings::TRADE_NAME), $company);
$this->registerMetaTag(['name' => 'description', 'content' => Yii::$app->settings->get(Settings::TRADE_NAME) . ' компании ' . $company->getFullName()]);
$this->registerMetaTag(['name' => 'keywords', 'content' => Html::encode($company->form . ', ' . $company->name . ($company->geo ? ', ' . $company->geo->name : '') . ', ' . Yii::$app->settings->get(Settings::TRADE_NAME))]);

?>
<div class="row">
    <div class="col-md-9 col-sm-9 col-xs-12">

        <?= ShowContentBlock::widget([
            'module' => ContentBlock::MODULE_TRADE,
            'place' => ContentBlock::PLACE_MAIN,
            'page' => ContentBlock::PAGE_LISTING,
            'category' => $category,
            'count' => 1
        ]) ?>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <?php CompanyHelper::companyBreadcrumbs($company, 'trades') ?>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <h1>Товары <?= $company->getFullName() ?></h1>
            </div>
            <div class="col-md-12 col-sm-12 hidden-xs">
                <?= $this->render('_actions-top', ['company' => $company]) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="kk-menu">
                    <?= $this->render('_menu', ['company' => $company]) ?>
                </div>

                <?= Html::beginForm($company->getUrl('trades'), 'get', ['class' => 'form-inline filter-form-auto']) ?>
                    Раздел: <?= Html::dropDownList(
                                'category',
                                $category ? $category->id : null,
                                CompanyHelper::companyTradeCategoriesItems($company),
                                ['class' => 'form-control input-md', 'title' => 'Раздел', 'prompt' => 'Все']
                            ) ?>
                <?= Html::endForm() ?>

                <div class="card-items-block">
                    <?= $this->render('@frontend/views/trade/trade/card-items-block', [
                        'provider' => $provider,
                        'inCompany' => true,
                    ]) ?>
                </div>

                <div class="list-pagination has-overlay">
                    <?php if ($provider->pagination->pageCount > $provider->pagination->page + 1): ?>
                        <br>
                        <p
                           id="list-btn-scroll"
                           class="btn btn-list"
                           data-url="<?= $provider->pagination->createUrl($provider->pagination->page + 1) ?>"
                        >Показать ещё</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="md-12 col-sm-12 col-xs-12">
                <?= ShowContentBlock::widget([
                    'module' => ContentBlock::MODULE_TRADE,
                    'place' => ContentBlock::PLACE_MAIN,
                    'page' => ContentBlock::PAGE_LISTING,
                    'category' => $category,
                    'start' => 2
                ]) ?>
            </div>
        </div>
    </div>

    <!-- right col -->
    <div class="col-md-3 col-sm-3 hidden-xs">
        <div id="rightCol">
            <div style="margin: 10px 0;"><img src="/img/234.png" class="img-responsive" alt=""></div>

            <?= ShowContentBlock::widget([
                'module' => ContentBlock::MODULE_TRADE,
                'place' => ContentBlock::PLACE_SIDEBAR_RIGHT,
                'page' => ContentBlock::PAGE_LISTING,
                'category' => $category,
            ]) ?>
        </div><!-- // right col -->
    </div>
</div>
