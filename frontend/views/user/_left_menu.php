<?php

use yii\helpers\Url;

/* @var $user \core\entities\User\User */
/* @var $link string */

$link = Yii::$app->controller->action->id;

?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">id <?= $user->id ?></h3>
    </div>
    <div class="panel-body">
        <ul class="nav nav-pills nav-stacked">
            <li role="presentation"<?= $link == 'profile' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/user/account/profile']) ?>">Мои данные</a></li>
            <li role="presentation"><a href="<?= Url::to(['/user/account/profile']) ?>">Сообщения (0)</a></li>
            <li role="presentation"><a href="<?= Url::to(['/user/account/profile']) ?>">Объявления</a></li>
            <li role="presentation"><a href="<?= Url::to(['/user/account/profile']) ?>">Служба поддержки</a></li>
        </ul>
    </div>
</div>
