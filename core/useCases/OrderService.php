<?php

namespace core\useCases;


use core\components\Cart\Cart;
use core\entities\Order\Order;
use core\entities\Order\OrderItem;
use core\entities\Trade\Trade;
use core\forms\OrderForm;
use core\jobs\SendMailJob;
use core\repositories\OrderRepository;
use core\services\TransactionManager;
use Yii;
use yii\helpers\ArrayHelper;

class OrderService
{
    private $repository;
    private $transaction;

    public function __construct(OrderRepository $repository, TransactionManager $transaction)
    {
        $this->repository = $repository;
        $this->transaction = $transaction;
    }

    public function create(OrderForm $form): void
    {
        /* @var $newOrders Order[] */
        $newOrders = [];
        $this->transaction->wrap(function () use ($form, &$newOrders) {
            foreach ($form->amounts as $orderNum => $amounts) {
                if (!count($amounts) || !($companyId = Trade::find()->select('company_id')->where(['id' => key($amounts)])->scalar())) {
                    continue;
                }
                $order = Order::create(
                    $companyId,
                    Yii::$app->user->id,
                    ArrayHelper::getValue($form->deliveries, $orderNum) ?: null,
                    ArrayHelper::getValue($form->comments, $orderNum, ''),
                    $form->name,
                    $form->phone,
                    $form->email,
                    $form->address
                );
                $this->repository->save($order);
                $hasItems = false;
                foreach ($amounts as $productId => $amount) {
                    if (!$amount) {
                        continue;
                    }
                    $orderItem = OrderItem::create($order->id, $productId, $amount);
                    $this->repository->saveItem($orderItem);
                    $hasItems = true;
                }
                if (!$hasItems) {
                    $this->repository->remove($order);
                } else {
                    $newOrders[] = $order;
                }
            }
            (new Cart(Yii::$app->user->identity))->clear(); // Очистка корзины
        });

        //Yii::debug($newOrders);
        // Отправка email продавцам о новом заказе
        foreach ($newOrders as $order) {
            Yii::debug("ORDER PUSH " . $order->id);
            Yii::$app->queue->push(new SendMailJob([
                'view' => 'order/new-order-received',
                'params' => ['order' => $order],
                'subject' => 'Новый заказ на ' . Yii::$app->name,
                'to' => [$order->forCompany->email => $order->forCompany->getFullName()],
            ]));
        }
    }
}