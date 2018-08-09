<?php
use yii\helpers\Html;
use frontend\widgets\ContentBlock\ShowContentBlock;

/* @var $block \core\entities\ContentBlock */
/* @var $items string|\core\entities\Board\Board[]|\core\entities\Trade\Trade[]|\core\entities\Company\Company[] */

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
                            <img src="<?= $item->getMainPhotoUrl() ?>" alt="<?= Html::encode($item->name) ?>">
                        </a>
                    </div>
                    <div class="text-col">
                        <a href="<?= $item->getUrl() ?>">
                            <?= Html::encode($item->name) ?>
                        </a>
                    </div>
                    <div class="price-col"><?= $item->getFullPriceString() ?></div>
                    <div class="desc-col"><?= $item->getContentDescription() ?></div>
                    <?php ShowContentBlock::getVendInfo($block, $item) ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endforeach; ?>