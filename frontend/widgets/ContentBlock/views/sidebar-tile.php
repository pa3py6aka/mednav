<?php
use frontend\widgets\ContentBlock\ShowContentBlock;

/* @var $block \core\entities\ContentBlock */
/* @var $items array|\core\components\ContentBlocks\ContentBlockInterface[] */

?>
<div class="sidebar-title"><?= $block->show_title ? $block->name : '' ?></div>
<?php foreach ($items as $item): ?>
    <div class="sidebar-block-tile">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <?php if ($block->type == \core\entities\ContentBlock::TYPE_HTML): ?>
                    <?= $item ?>
                <?php else: ?>
                    <div class="block-img">
                        <a href="<?= $item->getUrl() ?>">
                            <img src="<?= $item->getMainPhotoUrl() ?>" alt="<?= $item->getContentName() ?>">
                        </a>
                    </div>
                    <div class="text-col">
                        <a href="<?= $item->getUrl() ?>"><?= $item->getContentName() ?></a><?= $item->getContentBlockRegionString() ?>
                    </div>
                    <div class="price-col"><?= $item->getFullPriceString() ?></div>
                    <div class="desc-col"><?= $item->getContentDescription() ?></div>
                    <?php ShowContentBlock::getVendInfo($block, $item) ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endforeach; ?>