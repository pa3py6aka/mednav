<?php
use yii\helpers\Html;

/* @var $block \core\entities\ContentBlock */
/* @var $items string|\core\entities\Board\Board[]|\core\entities\Trade\Trade[]|\core\entities\Company\Company[] */

?>
<div class="sidebar-title"><?= $block->show_title ? $block->name : '' ?></div>
<div class="row">
    <?php foreach ($items as $item): ?>
        <div class="content-block-string">
            <?php if ($block->type == \core\entities\ContentBlock::TYPE_HTML): ?>
                <?= $item ?>
            <?php else: ?>
                <div class="col-md-2">
                    <a href="<?= $item->getUrl() ?>">
                        <img src="<?= $item->getMainPhotoUrl() ?>" alt="<?= Html::encode($item->name) ?>" width="69px" class="img-responsive">
                    </a>
                </div>
                <div class="col-md-9">
                    <div class="text-col">
                        <a href="<?= $item->getUrl() ?>">
                            <?= Html::encode($item->name) ?>
                        </a>
                    </div>
                    <div class="price-col"><?= $item->getFullPriceString() ?></div>
                    <div class="desc-col"><?= $item->getContentDescription() ?></div>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

