<?php
use yii\helpers\Url;
use core\entities\Currency;

/* @var $tab string */

?>
<ul class="nav nav-tabs" role="tablist">
    <li role="presentation"<?= $tab == 'main' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/trade/settings/main']) ?>">Основные</a></li>
    <li role="presentation"<?= $tab == 'index-page' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/trade/settings/index-page']) ?>">Главная страница</a></li>
    <li role="presentation"<?= strpos($tab, 'currencies') ? ' class="active"' : '' ?>><a href="<?= Url::to(['/settings/currencies', 'for' => Currency::MODULE_TRADE]) ?>">Ден. ед.</a></li>

</ul>
