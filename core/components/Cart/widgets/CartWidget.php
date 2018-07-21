<?php

namespace core\components\Cart\widgets;


use core\components\Cart\Cart;
use core\entities\User\User;
use yii\base\Widget;

class CartWidget extends Widget
{
    /* @var User|null */
    private $user;

    public function init()
    {
        parent::init();
        $this->user = \Yii::$app->user->identity;
    }

    public function run()
    {
        $cart = new Cart($this->user);
        $cartItems = $cart->getItemsCount();
        return $this->render('cart-block', [
            'cartItems' => $cartItems,
        ]);
    }
}