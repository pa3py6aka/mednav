<?php
use yii\helpers\Url;
use core\entities\Currency;

/* @var $tab string */

?>
<ul class="nav nav-tabs" role="tablist">
    <li role="presentation"<?= $tab == 'main' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/board/settings/main']) ?>">Основные</a></li>
    <li role="presentation"<?= $tab == 'index-page' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/board/settings/index-page']) ?>">Главная страница</a></li>
    <li role="presentation"<?= $tab == 'terms' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/board/settings/terms']) ?>">Сроки</a></li>
    <?php /*<li role="presentation"<?= $tab == 'special' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/board/settings/special']) ?>">Спецразмещение</a></li> */ ?>
    <li role="presentation"<?= $tab == 'parameters' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/board/parameters/index']) ?>">Параметры</a></li>
    <li role="presentation"<?= strpos($tab, 'currencies') ? ' class="active"' : '' ?>><a href="<?= Url::to(['/settings/currencies', 'for' => Currency::MODULE_BOARD]) ?>">Ден. единицы</a></li>
</ul>
