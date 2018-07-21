<?php
use yii\helpers\Url;

/* @var $productId integer */

?>
<form class="form-inline order-button-form has-overlay" action="<?= Url::to(['/user/order/cart']) ?>" method="post">
    <div class="input-group spinner">
        <input type="text" class="form-control product-amount" value="1" min="0" max="9999999">
        <div class="input-group-btn-vertical">
            <button class="btn btn-default" type="button"><i class="fa fa-caret-up"></i></button>
            <button class="btn btn-default" type="button"><i class="fa fa-caret-down"></i></button>
        </div>
    </div>
    <!-- <input class="form-control" placeholder="1" value="" size="4" type="text" id="search-input"> -->
    <button class="form-control btn kt-btn-cart" type="submit" data-product-id="<?= $productId ?>" data-button="order-button">Заказать</button>
</form>
