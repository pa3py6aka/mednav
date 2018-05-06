<?php

use core\helpers\BoardHelper;
use frontend\widgets\BoardCategoriesListWidget;

/* @var $categories \core\entities\Board\BoardCategory[] */
/* @var $geo \core\entities\Geo|null */
/* @var $isMainPage bool */

?>
<div class="list-section">
    <div class="row">
        <?php foreach ($categories as $category): ?>
        <div class="col-md-4 col-sm-12 col-xs-12">
            <div class="list-section-parent">
                 <span>&ndash;</span> <a href="<?= BoardHelper::categoryUrl($category, $geo) ?>"><?= $category->name ?></a>
                 <sup class="list-section-count"><?= BoardHelper::getCountInCategory($category) ?></sup>
            </div>
            <?= BoardCategoriesListWidget::generateList($category, $geo, $isMainPage) ?>
        </div>
        <?php endforeach; ?>
    </div>
</div>
