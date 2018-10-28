<?php

/* @var $this yii\web\View */
/* @var $fullOrder \core\entities\Order\UserOrder */


$this->title = 'Заказ оформлен';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-9 col-sm-9 col-xs-12">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12"><h1>Ваш заказ <?= \core\helpers\OrderHelper::getOrderNumbersString($fullOrder) ?> успешно оформлен</h1></div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="kk-content">

                </div>
            </div>
        </div>
    </div>

    <!-- right col -->
    <div class="col-md-3 col-sm-3 hidden-xs">
        <div id="rightCol">

        </div><!-- // right col -->
    </div>
</div>
