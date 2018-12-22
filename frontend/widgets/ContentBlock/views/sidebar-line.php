<?php
use frontend\widgets\ContentBlock\ShowContentBlock;

/* @var $block \core\entities\ContentBlock */
/* @var $items array|\core\components\ContentBlocks\ContentBlockInterface[] */

?>
<div class="sidebar-title"><?= $block->show_title ? $block->name : '' ?></div>
<?php foreach ($items as $item): ?>
    <div class="sidebar-item-string">
        <div class="row">
            <?php if ($block->type == \core\entities\ContentBlock::TYPE_HTML): ?>
                <div class="col-xs-12">
                    <?= $item ?>
                </div>
            <?php else: ?>
                <div class="col-md-4">
                    <div class="sidebar-block-string-img">
                        <a href="<?= $item->getUrl() ?>">
                            <img src="<?= $item->getMainPhotoUrl() ?>" alt="<?= $item->getContentName() ?>" class="img-responsive">
                        </a>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="text-col">
                        <a href="<?= $item->getUrl() ?>"><?= $item->getContentName() ?></a><?= $item->getContentBlockRegionString() ?>
                    </div>
                    <div class="price-col"><?= $item->getFullPriceString() ?></div>
                    <div class="desc-col"><?= $item->getContentDescription() ?></div>
                    <?php ShowContentBlock::getVendInfo($block, $item) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>