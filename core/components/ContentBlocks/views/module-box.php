<?php

use yii\helpers\Url;
use core\entities\ContentBlock;

/* @var $module int */
/* @var $name string */
/* @var $places array */
/* @var $widget \core\components\ContentBlocks\ContentBlocksWidget */

$isMainPage = $module == ContentBlock::MODULE_MAIN_PAGE;

?>
<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title"><?= $name ?></h3>
    </div>
    <div class="box-body table-responsive">
        <table class="table table-bordered table-striped">
            <tr>
                <th>Наименование</th>
                <th>Тип вывода</th>
                <th>Статус</th>
                <th></th>
            </tr>
            <?php if (!$isMainPage): ?>
            <tr>
                <td colspan="4" class="tr-row">
                    Listing
                </td>
            </tr>
            <?php endif; ?>
            <?php foreach ($places as $place => $placeName): ?>
                <tr>
                    <td colspan="4" class="tr-row">
                        <?= !$isMainPage ? '&nbsp;&nbsp;' : '' ?><?= $placeName ?>
                        <a href="<?= Url::to(['add-block', 'module' => $module, 'place' => $place, 'page' => ContentBlock::PAGE_LISTING]) ?>" class="btn btn-primary btn-xs pull-right">Добавить блок</a>
                    </td>
                </tr>
                <?php foreach ($widget->getBlocksFor($module, $place, ContentBlock::PAGE_LISTING) as $block): ?>
                    <tr>
                        <td><?= !$isMainPage ? '&nbsp;&nbsp;&nbsp;&nbsp;' : '' ?>&nbsp;&nbsp;<a href="<?= Url::to(['edit', 'id' => $block->id])?>"><?= $block->name ?></a></td>
                        <td><?= $block->getTypeName() ?></td>
                        <td>
                            <a href="#" class="status-switcher btn btn-xs btn-<?= $block->enable ? 'success' : 'danger' ?>" data-block-id="<?= $block->id ?>">
                                <?= $block->enable ? 'On' : 'Off' ?>
                            </a>
                        </td>
                        <td>
                            <a href="<?= Url::to(['delete', 'id' => $block->id]) ?>" data-confirm="Удалить этот блок?" class="btn btn-danger btn-xs">
                                <span class="fa fa-remove"></span>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>

            <?php if (!$isMainPage): ?>
                <tr>
                    <td colspan="4" class="tr-row">
                        Page content
                    </td>
                </tr>
                <?php foreach ($places as $place => $placeName): ?>
                    <tr>
                        <td colspan="4" class="tr-row">
                            <?= !$isMainPage ? '&nbsp;&nbsp;' : '' ?><?= $placeName ?>
                            <a href="<?= Url::to(['add-block', 'module' => $module, 'place' => $place, 'page' => ContentBlock::PAGE_VIEW]) ?>" class="btn btn-primary btn-xs pull-right">Добавить блок</a>
                        </td>
                    </tr>
                    <?php foreach ($widget->getBlocksFor($module, $place, ContentBlock::PAGE_VIEW) as $block): ?>
                        <tr>
                            <td><?= !$isMainPage ? '&nbsp;&nbsp;&nbsp;&nbsp;' : '' ?>&nbsp;&nbsp;<a href="<?= Url::to(['edit', 'id' => $block->id])?>"><?= $block->name ?></a></td>
                            <td><?= $block->getTypeName() ?></td>
                            <td>
                                <a href="#" class="status-switcher btn btn-xs btn-<?= $block->enable ? 'success' : 'danger' ?>" data-block-id="<?= $block->id ?>">
                                    <?= $block->enable ? 'On' : 'Off' ?>
                                </a>
                            </td>
                            <td>
                                <a href="<?= Url::to(['delete', 'id' => $block->id]) ?>" data-confirm="Удалить этот блок?" class="btn btn-danger btn-xs">
                                    <span class="fa fa-remove"></span>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </div>
</div>
