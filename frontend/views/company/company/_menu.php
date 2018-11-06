<?php

/* @var $company \core\entities\Company\Company */

?>
<ul>
    <li><a href="<?= $company->getUrl() ?>">О компании</a></li>
    <li><a href="<?= $company->getUrl('contacts') ?>">Контакты</a></li>
    <li><a href="<?= $company->getUrl('trades') ?>">Товары</a> <sup><?= $company->getCountFor('trades') ?></sup></li>
    <li><a href="<?= $company->getUrl('boards') ?>">Объявления</a> <sup><?= $company->getCountFor('boards') ?></sup></li>
    <li><a href="<?= $company->getUrl('cnews') ?>">Новости компании</a> <sup><?= $company->getCountFor('сnews') ?></sup></li>
    <li><a href="<?= $company->getUrl('articles') ?>">Статьи</a> <sup><?= $company->getCountFor('articles') ?></sup></li>
    <li><a href="#">Акции</a> <sup>1200</sup></li>
</ul>
