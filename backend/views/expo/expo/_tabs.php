<?php
use yii\helpers\Url;
use core\entities\Expo\Expo;

/* @var $tab string */

?>
<ul class="nav nav-tabs" role="tablist">
    <li role="presentation"<?= $tab == 'active' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/expo/expo/active']) ?>">Размещённые</a></li>
    <li role="presentation"<?= $tab == 'moderation' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/expo/expo/moderation']) ?>">На проверку (<?= Expo::find()->onModeration()->count() ?>)</a></li>
    <li role="presentation"<?= $tab == 'deleted' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/expo/expo/deleted']) ?>">Удалённые</a></li>
</ul>
