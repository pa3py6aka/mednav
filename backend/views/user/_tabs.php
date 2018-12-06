<?php
use yii\helpers\Url;
use core\entities\User\User;

/* @var $tab string */

?>
<ul class="nav nav-tabs" role="tablist">
    <li role="presentation"<?= $tab === 'active' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/user/active']) ?>">Активные</a></li>
    <li role="presentation"<?= $tab === 'moderation' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/user/moderation']) ?>">На проверку (<?= User::find()->onModeration()->count() ?>)</a></li>
    <li role="presentation"<?= $tab === 'deleted' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/user/deleted']) ?>">Удалённые</a></li>
</ul>
