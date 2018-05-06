<?php

use core\helpers\BoardHelper;
use frontend\widgets\BoardCategoriesListWidget;

/* @var $widget BoardCategoriesListWidget */
/* @var $categories \core\entities\Board\BoardCategory[] */
?>
<div class="list-section">
    <div class="row">
        <?php foreach ($categories as $category): ?>
        <div class="col-md-4 col-sm-12 col-xs-12">
            <div class="list-section-parent">
                 <span>&ndash;</span> <a href="<?= BoardHelper::categoryUrl($category, $widget->region) ?>"><?= $category->name ?></a>
                 <sup class="list-section-count"><?= BoardHelper::getCountInCategory($category) ?></sup>
            </div>
            <?= $widget->generateList($category) ?>
        </div>
        <?php endforeach; ?>
    </div>
</div>
