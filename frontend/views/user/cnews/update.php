<?php

use core\forms\CNews\CNewsForm;
use core\components\Settings;
use frontend\widgets\PhotosManagerWidget;

/* @var $this yii\web\View */
/* @var $model CNewsForm */
/* @var $news \core\entities\CNews\CNews */
/* @var $photosForm \core\forms\manage\PhotosForm */
/* @var $tab string */

$this->title = 'Личный кабинет | Редактирование новости';

?>
<div class="row">
    <div class="col-md-3">
        <?= $this->render('@frontend/views/user/_left_menu', ['user' => $this->params['user']]) ?>
    </div>
    <div class="col-md-9">
        <?= \frontend\widgets\AccountBreadcrumbs::show([['label' => Yii::$app->settings->get(Settings::CNEWS_NAME_UP), 'url' => ['/user/cnews/active']], 'Редактирование новости']) ?>
        <h1>Редактирование новости</h1>

        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation"<?= $tab == 'main' ? ' class="active"' : '' ?>><a href="#main" role="tab" id="main-tab" data-toggle="tab" aria-controls="main">Основные данные</a></li>
            <li role="presentation"<?= $tab == 'photos' ? ' class="active"' : '' ?>><a href="#photos" role="tab" id="photos-tab" data-toggle="tab" aria-controls="photos">Фотографии</a></li>
        </ul>
        <div class="tab-content">
            <!-- Основные данные -->
            <div class="tab-pane fade<?= $tab == 'main' ? ' active in' : '' ?>" role="tabpanel" id="main" aria-labelledby="main-tab">
                <?= $this->render('_form', ['model' => $model]) ?>
            </div>
            <!-- Фотографии -->
            <div class="tab-pane fade<?= $tab == 'photos' ? ' active in' : '' ?>" role="tabpanel" id="photos" aria-labelledby="photos-tab">
                <br>
                <?= PhotosManagerWidget::widget(['entityId' => $news->id, 'photos' => $news->photos]) ?>
            </div>
        </div>
    </div>
</div>