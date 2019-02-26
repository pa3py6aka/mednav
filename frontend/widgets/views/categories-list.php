<?php

use frontend\widgets\CategoriesListWidget;

/* @var $widget CategoriesListWidget */
/* @var $categories \core\entities\Board\BoardCategory[]|\core\entities\Company\CompanyCategory[] */
?>
<div class="list-section">
    <div class="row">
        <?= $widget->generateList($categories) ?>
        <?php /*foreach ($categories as $category): ?>
        <div class="col-md-4 col-sm-12 col-xs-12">
            <div class="list-section-parent">
                 <span>&ndash;</span> <a href="<?= $widget->helperClass::categoryUrl($category, $widget->region, false, false) ?>"><?= $category->name ?></a>
                 <sup class="list-section-count"><?= $widget->helperClass::getCountInCategory($category) ?></sup>
            </div>
            <?= $widget->generateList($category) ?>
        </div>
        <?php endforeach;*/ ?>
    </div>
</div>
