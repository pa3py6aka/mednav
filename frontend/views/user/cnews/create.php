<?php

use core\components\ImageManager\ImageManagerAsset;
use yii\helpers\Url;
use core\components\Settings;

/* @var $this yii\web\View */
/* @var $model \core\forms\CNews\CNewsForm */

$this->title = 'Личный кабинет | Добавление новости';
$this->registerJsVar('_ImageUploadAction', Url::to(['/user/cnews/upload']));
ImageManagerAsset::register($this);

?>
<div class="row">
    <div class="col-md-3">
        <?= $this->render('@frontend/views/user/_left_menu', ['user' => $this->params['user']]) ?>
    </div>

    <div class="col-md-9">
        <?= \frontend\widgets\AccountBreadcrumbs::show([['label' => Yii::$app->settings->get(Settings::CNEWS_NAME_UP), 'url' => ['/user/cnews/active']], 'Новая новость']) ?>
        <h1>Новая новость</h1>

        <?= $this->render('_form', ['model' => $model]) ?>
    </div>
</div>