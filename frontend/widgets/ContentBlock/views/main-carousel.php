<?php
use yii\helpers\Html;

/* @var $block \core\entities\ContentBlock */
/* @var $items string|\core\entities\Board\Board[]|\core\entities\Trade\Trade[]|\core\entities\Company\Company[] */

?>
<div class="sidebar-title"><?= $block->show_title ? $block->name : '' ?></div>
<div class="brand-baner">
    <div class="brand-baner__slider owl-carousel">
        <?php foreach ($items as $item): ?>
            <?php if ($block->type == \core\entities\ContentBlock::TYPE_HTML): ?>
                <div>
                    <?= $item ?>
                </div>
            <?php else: ?>
                <div class="brand-baner__item">
                    <div class="content-block-caorusel-img">
                        <a href="<?= $item->getUrl() ?>">
                            <img src="<?= $item->getMainPhotoUrl() ?>" alt="<?= Html::encode($item->name) ?>" align="center" class="img-responsive">
                        </a>
                    </div>
                    <div class="text-col">
                        <a href="<?= $item->getUrl() ?>">
                            <?= Html::encode($item->name) ?>
                        </a>
                    </div>
                    <div class="price-col"><?= $item->getFullPriceString() ?></div>
                    <div class="desc-col"><?= $item->getContentDescription() ?></div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>

