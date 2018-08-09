<?php

use yii\helpers\Html;
use core\helpers\CompanyHelper;
use core\helpers\TextHelper;
use frontend\widgets\message\MessageWidget;
use frontend\widgets\ContentBlock\ShowContentBlock;
use core\entities\ContentBlock;

/* @var $this \yii\web\View */
/* @var $company \core\entities\Company\Company */
/* @var $page string */

$this->title = $company->getTitle();
$this->registerMetaTag(['name' => 'description', 'content' => Html::encode($company->description)]);
$this->registerMetaTag(['name' => 'keywords', 'content' => Html::encode($company->getTagsString())]);

?>
<div class="row">
    <div class="col-md-9 col-sm-9 col-xs-12">

        <?= ShowContentBlock::widget([
            'module' => ContentBlock::MODULE_COMPANY,
            'place' => ContentBlock::PLACE_MAIN,
            'page' => ContentBlock::PAGE_VIEW,
            'count' => 1,
            'entity' => $company,
        ]) ?>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <?php CompanyHelper::companyBreadcrumbs($company, $page) ?>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12"><h1><?= $company->getFullName() ?></h1></div>
        </div>

        <div class="row">
            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="">
                    <!--на остальных страницах, ссылка ведет на страницу "О компании"-->
                    <a class="fancybox" href="<?= $company->getUrl() ?>" data-fancybox-group="gallery">
                        <img src="<?= $company->getLogoUrl() ?>" alt="" class="img-responsive">
                    </a>
                </div>
                <div class="kk-btn">
                    <?= MessageWidget::widget([
                        'toUser' => $company->user,
                        'subjectType' => MessageWidget::SUBJECT_TYPE_INPUT,
                        'btnClass' => 'kt-btn-cart',
                    ]) ?>
                    <a href="#ModalContact" class="form-control btn kk-btn-contact" data-toggle="modal">
                        <span class="glyphicon glyphicon-user btn-xs"></span> Добавить в контакты
                    </a>

                    <!-- modal add contact-->
                    <div id="ModalContact" class="modal fade">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    <h4>Уведомление</h4>
                                </div>
                                <div class="modal-body">
                                    Компания <b><?= $company->getFullName() ?></b> добавлена в ваш список контактов.
                                    <!--Уведомление, что данная компания уже в списке контактов "Компания <b>ООО "Название компании"</b> уже находится в вашем списке контактов." -->
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--// modal add contact-->
                </div>
            </div>
            <div class="col-md-8 col-sm-8 col-xs-12">
                <div class="kk-menu">
                    <?= $this->render('_menu', ['company' => $company]) ?>
                </div>
                <div class="kk-content">
                    <?php if ($page == 'main'): ?>
                        <?= TextHelper::out($company->description, true) ?>

                        <div class="kk-gallery">
                            <?php foreach ($company->photos as $photo): ?>
                                <a class="fancybox" href="<?= $photo->getUrl('max') ?>" data-fancybox-group="gallery">
                                    <img src="<?= $photo->getUrl() ?>" alt="" class="img-responsive">
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <?= $this->render('_' . $page, ['company' => $company]) ?>
                    <?php endif; ?>
                    <div class="clearfix"></div>
                </div>

                <div class="id-page">
                    <span class="glyphicon glyphicon-eye-open btn-xs"></span>
                    <?= $company->views ?>, на сайте с <?= Yii::$app->formatter->asDate($company->created_at) ?>
                </div>
            </div>
        </div>

        <?= ShowContentBlock::widget([
            'module' => ContentBlock::MODULE_COMPANY,
            'place' => ContentBlock::PLACE_MAIN,
            'page' => ContentBlock::PAGE_VIEW,
            'start' => 2,
            'entity' => $company,
        ]) ?>
    </div>

    <!-- right col -->
    <div class="col-md-3 col-sm-3 hidden-xs">
        <div id="rightCol">
            <div style="margin: 10px 0;"><img src="/img/234.png" class="img-responsive" alt=""></div>

            <?= ShowContentBlock::widget([
                'module' => ContentBlock::MODULE_COMPANY,
                'place' => ContentBlock::PLACE_SIDEBAR_RIGHT,
                'page' => ContentBlock::PAGE_VIEW,
                'entity' => $company,
            ]) ?>
        </div><!-- // right col -->
    </div>
</div>
