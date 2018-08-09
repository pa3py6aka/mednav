<?php
use yii\helpers\Html;

/* @var $block \core\entities\ContentBlock */
/* @var $items string|\core\entities\Board\Board[]|\core\entities\Trade\Trade[]|\core\entities\Company\Company[] */
$i = 0;

?>
<div class="sidebar-title"><?= $block->show_title ? $block->name : '' ?></div>
<div style="margin: 0 0 15px 0;">
    <?php foreach ($items as $n => $item): ?>
        <?php if (!(($i+1) % 4) && $i != 0): ?>
            </div>
        <?php endif; ?>
        <?php if (!(($i+1) % 4) || $i == 0): ?>
            <div class="row">
        <?php endif; ?>

        <div class="col-md-4">
            <?php if ($block->type == \core\entities\ContentBlock::TYPE_HTML): ?>
                <?= $item ?>
            <?php else: ?>
                <div class="content-block-tile-img">
                    <a href="<?= $item->getUrl() ?>">
                        <img src="<?= $item->getMainPhotoUrl() ?>" alt="<?= Html::encode($item->name) ?>" class="img-responsive">
                    </a>
                </div>
                <div class="text-col">
                    <a href="<?= $item->getUrl() ?>">
                        <?= Html::encode($item->name) ?>
                    </a>
                </div>
                <div class="price-col"><?= $item->getFullPriceString() ?></div>
                <div class="desc-col"><?= $item->getContentDescription() ?></div>
            <?php endif; ?>
        </div>
    <?php $i++;
    endforeach; ?>
            </div>
</div>

