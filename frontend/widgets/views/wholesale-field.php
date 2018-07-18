<?php
use yii\bootstrap\Html;

/* @var $model \core\forms\manage\Trade\TradeManageForm */

?>
<div class="container-fluid no-padding" style="box-shadow:none;">
    <div id="wholesales-block" class="hidden col-xs-12 col-sm-10 col-md-9 col-lg-7 no-padding">
        <label class="control-label" for="trademanageform-price">Оптовые цены</label>
        <?php for ($i = 0;$i < 3;$i++): ?>
            <div class="container-fluid no-padding" style="box-shadow:none;">
                <div class="col-xs-5 no-padding">
                    <?= Html::activeInput('text', $model, 'wholeSalePrice[' . $i . ']', ['class' => 'form-control']) ?>
                </div>
                <div class="col-xs-2 lh30">
                    <span class="pull-left whole-sile-price">руб.,</span><span class="pull-right">От</span>
                </div>
                <div class="col-xs-5">
                    <?= Html::activeInput('text', $model, 'wholeSaleFrom[' . $i . ']', ['class' => 'form-control whole-sile-from']) ?>
                </div>
            </div>
        <?php endfor; ?>
    </div>
</div>

