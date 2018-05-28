<?php

use core\components\ImageManager\ImageManagerAsset;
use core\forms\manage\Board\BoardManageForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model BoardManageForm */


$this->title = 'Личный кабинет | Добавление объявления';
$this->registerJsVar('_ImageUploadAction', Url::to(['/user/board/upload']));
ImageManagerAsset::register($this);

?>
<div class="row">
    <div class="col-md-3">
        <?= $this->render('@frontend/views/user/_left_menu', ['user' => $this->params['user']]) ?>
    </div>

    <div class="col-md-9">
        <?= \frontend\widgets\AccountBreadcrumbs::show(['Новое объявление']) ?>
        <h1>Новое объявление</h1>

        <?= $this->render('_form', ['model' => $model]) ?>
    </div>
</div>