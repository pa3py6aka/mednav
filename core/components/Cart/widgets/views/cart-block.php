<?php
use yii\helpers\Url;

/* @var $cartItems int */

?>
<!--cart block-->
<div class="col-md-2 col-sm-3 col-xs-12">
    <div class="cart-block-box">
        <a href="<?= Url::to(['/user/order/cart']) ?>" class="cart-block">
            <span class="glyphicon glyphicon-shopping-cart btn-xs"></span>
            Корзина <span class="badge products-amount"><?= $cartItems ?></span>
        </a>
    </div>
</div>
<!-- // cart block-->
