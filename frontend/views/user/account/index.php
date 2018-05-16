<?php


use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $user \core\entities\User\User int */


$this->title = 'Личный кабинет';
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="row">
    <div class="col-md-3">
        <?= $this->render('@frontend/views/user/_left_menu', ['user' => $user]) ?>
    </div>

    <div class="col-md-9">
        <?= \frontend\widgets\AccountBreadcrumbs::show() ?>

        <?php if ($user->isProfileEmpty()): ?>
        <br>
        <div id="info" class="alert-info alert fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>

            Добро пожаловать на сайт http://mednav.ru<br>
            Для начала работы, заполните форму <a href="<?= Url::to(['/user/account/profile']) ?>">вашего профиля</a>
            и данные о компании

        </div>
        <?php endif; ?>
    </div>
</div>
