<?php

use core\components\Settings;
use core\components\ImageManager\ImageManagerAsset;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model \core\forms\Expo\ExpoForm */

$this->title = 'Личный кабинет | Добавление выставки';
$this->registerJsVar('_ImageUploadAction', Url::to(['/user/expo/upload']));
ImageManagerAsset::register($this);

?>
<div class="row">
    <div class="col-md-3">
        <?= $this->render('@frontend/views/user/_left_menu', ['user' => $this->params['user']]) ?>
    </div>

    <div class="col-md-9">
        <?= \frontend\widgets\AccountBreadcrumbs::show([['label' => Yii::$app->settings->get(Settings::EXPO_NAME_UP), 'url' => ['/user/expo/active']], 'Новая выставка']) ?>
        <h1>Новая выставка</h1>

        <?= $this->render('_form', ['model' => $model]) ?>
    </div>
</div>