<?php

use yii\helpers\Html;
use core\helpers\TextHelper;

/* @var $page \core\entities\Page */

?>
<div class="row">
    <div class="col-md-3">
        <?= $this->render('@frontend/views/user/_left_menu', ['user' => $this->params['user']]) ?>
    </div>

    <div class="col-md-9">
        <?= \frontend\widgets\AccountBreadcrumbs::show(['Страницы']) ?>
        <h1><?= Html::encode($page->name) ?></h1>
        <?= TextHelper::out($page->content, 'pages') ?>
    </div>
</div>

