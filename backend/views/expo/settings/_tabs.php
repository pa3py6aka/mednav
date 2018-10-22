<?php
use yii\helpers\Url;

/* @var $tab string */

?>
<ul class="nav nav-tabs" role="tablist">
    <li role="presentation"<?= $tab == 'main' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/expo/settings/main']) ?>">Основные</a></li>
    <li role="presentation"<?= $tab == 'index-page' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/expo/settings/index-page']) ?>">Главная страница</a></li>
</ul>
