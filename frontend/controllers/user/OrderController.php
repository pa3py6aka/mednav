<?php

namespace frontend\controllers\user;


use core\components\Cart\actions\AddToCartAction;
use core\components\Cart\Cart;
use core\forms\OrderForm;
use core\useCases\OrderService;
use Yii;
use yii\base\Module;
use yii\base\UserException;
use yii\web\Controller;

class OrderController extends Controller
{
    private $service;

    public function __construct(string $id, Module $module, OrderService $service, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

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
        $form = new OrderForm();
        $form->load(Yii::$app->request->post());

        if ($form->validate()) {
            try {
                $this->service->create($form);
                Yii::$app->session->setFlash("success", "Заказ(ы) успешно оформлены.");
                return $this->redirect(['booked']);
            } catch (\DomainException $e) {
                throw new UserException($e->getMessage());
            }
        }

        throw new UserException(implode("<br>", $form->getFirstErrors()));
    }
}