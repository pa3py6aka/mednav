<?php

use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $user \core\entities\User\User int */


$this->title = 'Личный кабинет';

?>
<div class="row">
    <div class="col-md-3">
        <?= $this->render('@frontend/views/user/_left_menu', ['user' => $user]) ?>
    </div>

    <div class="col-md-9">
        <?= \frontend\widgets\AccountBreadcrumbs::show() ?>
        <br>
        <div id="info" class="alert-info alert fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            Добро пожаловать на сайт http://mednav.ru
        </div>
        <?php if ($user->isProfileEmpty()): ?>
        <div id="info" class="alert-danger alert fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>

            Для начала работы, заполните <?= \core\helpers\UserHelper::fillProfileMessage($user) ?>
        </div>
        <?php endif; ?>
    </div>
</div>
