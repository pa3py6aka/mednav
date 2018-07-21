<?php

namespace core\components\Cart\actions;


use core\components\Cart\Cart;
use core\entities\User\User;
use Yii;
use yii\base\Action;
use yii\base\InvalidArgumentException;

class AddToCartAction extends Action
{
    private $productId;
    private $amount;

    /* @var User|null */
    private $user;

    public function init()
    {
        $this->productId = (int) Yii::$app->request->post('productId');
        $this->amount = (int) Yii::$app->request->post('amount');
        $this->user = Yii::$app->user->identity;
        if (!$this->productId) {
            throw new InvalidArgumentException("ИД товара не определён.");
        }
        if (!$this->amount) {
            $this->amount = 1;
        }
    }

    public function run()
    {
        $cart = new Cart($this->user);
        $cartItemsCount = $cart->add($this->productId, $this->amount);
        return $this->controller->asJson(['result' => 'success', 'cartItemsCount' => $cartItemsCount]);
    }
}