<?php

use backend\assets\RegionsAttachAsset;
use backend\widgets\RegionsAttachWidget;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $category \core\entities\Board\BoardCategory */

RegionsAttachAsset::register($this);

?>
<?= Html::beginForm() ?>
<?= Html::hiddenInput('attach', 1) ?>
<div class="box box-default">
    <div class="box-header">
        <h3 class="box-title"><?= $category->name ?></h3>
    </div>
    <div class="box-body">
        <?= RegionsAttachWidget::widget([
            'attachedRegions' => $category->getRegions()->select('geo_id')->column(),
            'entityId' => $category->id,
        ]) ?>
    </div>
    <div class="box-footer">
        <?= Html::submitButton('Привязать отмеченные', ['class' => 'btn btn-success']) ?>
    </div>
</div>
<?= Html::endForm() ?>

<div class="modal fade" tabindex="-1" role="dialog" id="regionSettingsModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <div class="box box-primary" style="padding-top:10px;"></div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

