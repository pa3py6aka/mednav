<?php

use core\helpers\HtmlHelper;

/* @var $company \core\entities\Company\Company */

?>
<ul>
    <li><a href="<?= $company->getUrl() ?>">О компании</a></li>
    <li><a href="<?= $company->getUrl('contacts') ?>">Контакты</a></li>
    <?= HtmlHelper::showIfIs('<li><a href="' . $company->getUrl('trades') . '">Товары</a> <sup>' . $company->getCountFor('trades') . '</sup></li>', $company->getCountFor('trades')) ?>
    <?= HtmlHelper::showIfIs('<li><a href="' . $company->getUrl('boards') . '">Объявления</a> <sup>' . $company->getCountFor('boards') . '</sup></li>', $company->getCountFor('boards')) ?>
    <?= HtmlHelper::showIfIs('<li><a href="' . $company->getUrl('cnews') . '">Новости компании</a> <sup>' . $company->getCountFor('cnews') . '</sup></li>', $company->getCountFor('cnews')) ?>
    <?php //= HtmlHelper::showIfIs('<li><a href="' . $company->getUrl('articles') . '">Статьи</a> <sup>' . $company->getCountFor('articles') . '</sup></li>', $company->getCountFor('articles')) ?>
</ul>
