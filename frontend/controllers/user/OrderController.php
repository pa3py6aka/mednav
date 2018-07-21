<?php

namespace frontend\controllers\user;


use core\components\Cart\actions\AddToCartAction;
use core\components\Cart\Cart;
use core\forms\OrderForm;
use Yii;
use yii\base\UserException;
use yii\web\Controller;

class OrderController extends Controller
{
    public function actions()
    {
        return [
            'add-to-cart' => AddToCartAction::class,
        ];
    }

    public function actionCart()
    {
        $cartItems = (new Cart(Yii::$app->user->identity))->getItemsForOrder();
        $form = new OrderForm(Yii::$app->user->identity);

        return $this->render('cart', [
            'cartItems' => $cartItems,
            'orderForm' => $form,
        ]);
    }

    public function actionOrder()
    {
        throw new UserException("...");
    }
}