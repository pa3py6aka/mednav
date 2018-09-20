<?php

use core\components\ImageManager\ImageManagerAsset;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model \core\forms\Article\ArticleForm */

$this->title = 'Личный кабинет | Добавление статьи';
$this->registerJsVar('_ImageUploadAction', Url::to(['/user/article/upload']));
ImageManagerAsset::register($this);

?>
<div class="row">
    <div class="col-md-3">
        <?= $this->render('@frontend/views/user/_left_menu', ['user' => $this->params['user']]) ?>
    </div>

    <div class="col-md-9">
        <?= \frontend\widgets\AccountBreadcrumbs::show([['label' => 'Статьи', 'url' => ['/user/article/active']], 'Новая статья']) ?>
        <h1>Новая статья</h1>

        <?= $this->render('_form', ['model' => $model]) ?>
    </div>
</div>