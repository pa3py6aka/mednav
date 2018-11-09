<?php

use core\forms\manage\Board\BoardManageForm;
use frontend\widgets\PhotosManagerWidget;
use core\components\Settings;

/* @var $this yii\web\View */
/* @var $model BoardManageForm */
/* @var $board \core\entities\Board\Board */
/* @var $photosForm \core\forms\manage\PhotosForm */
/* @var $tab string */

$this->title = 'Личный кабинет | Редактирование объявления';

?>
<div class="row">
    <div class="col-md-3">
        <?= $this->render('@frontend/views/user/_left_menu', ['user' => $this->params['user']]) ?>
    </div>

    <div class="col-md-9">
        <?= \frontend\widgets\AccountBreadcrumbs::show([
            ['label' => Yii::$app->settings->get(Settings::BOARD_NAME_UP), 'url' => ['/user/board/active']],
            'Редактирование объявления'
        ]) ?>
        <h1>Редактирование объявления</h1>

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
                <?= PhotosManagerWidget::widget(['entityId' => $board->id, 'photos' => $board->photos]) ?>
            </div>
        </div>


    </div>
</div>