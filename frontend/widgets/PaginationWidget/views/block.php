<?php

use core\helpers\PaginationHelper;
use yii\widgets\LinkPager;

/* @var $type int */
/* @var $category \core\entities\Board\BoardCategory|null */
/* @var $geo \core\entities\Geo|null */
/* @var $provider \yii\data\ActiveDataProvider */

?>
<div class="list-pagination has-overlay">
    <?php if ($type === PaginationHelper::PAGINATION_NUMERIC): ?>
        <?= LinkPager::widget([
            'pagination' => $provider->pagination
        ]) ?>
    <?php else: ?>
        <br>
        <p id="list-btn-scroll" class="btn btn-list" data-url="<?= $provider->pagination->createUrl($provider->pagination->page + 1) ?>">Показать ещё</p>
    <?php endif; ?>
</div>
