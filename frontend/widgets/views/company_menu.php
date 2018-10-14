<?php
/* @var $company \core\entities\Company\Company */

?>
<div class="kk-menu">
    <ul>
        <li>См. также</li>
        <?php if ($company->getCountFor('trades')): ?>
            <li><a href="<?= $company->getUrl('trades') ?>">Товары</a> <sup><?= $company->getCountFor('trades') ?></sup></li>
        <?php endif; ?>
        <?php if ($company->getCountFor('boards')): ?>
            <li><a href="<?= $company->getUrl('boards') ?>">Объявления</a> <sup><?= $company->getCountFor('boards') ?></sup></li>
        <?php endif; ?>
        <?php if ($company->getCountFor('cnews')): ?>
            <li><a href="<?= $company->getUrl('cnews') ?>">Новости</a> <sup><?= $company->getCountFor('cnews') ?></sup></li>
        <?php endif; ?>

        <?php if ($company->getCountFor('articles')): ?>
            <li><a href="<?= $company->getUrl('articles') ?>">Статьи</a> <sup><?= $company->getCountFor('articles') ?></sup></li>
        <?php endif; ?>

        <li><a href="#">Акции</a> <sup>1200</sup></li>
        <li>компании</li>
    </ul>
</div>
