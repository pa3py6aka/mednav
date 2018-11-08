<?php

namespace core\useCases;


use core\components\Cart\Cart;
use core\entities\Order\Order;
use core\entities\Order\OrderItem;
use core\entities\Order\UserOrder;
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

    public function create(OrderForm $form): UserOrder
    {
        /* @var $newOrders Order[] */
        $newOrders = [];

        $userOrder = UserOrder::create(
            Yii::$app->user->id,
            $form->name,
            $form->phone,
            $form->email,
            $form->address
        );

        $this->transaction->wrap(function () use ($form, $userOrder, &$newOrders) {
            $this->repository->saveUserOrder($userOrder);

            foreach ($form->amounts as $orderNum => $amounts) {
                if (!count($amounts) || !($companyId = Trade::find()->select('company_id')->where(['id' => key($amounts)])->scalar())) {
                    continue;
                }
                $order = Order::create(
                    $userOrder->id,
                    $companyId,
                    Yii::$app->user->id,
                    ArrayHelper::getValue($form->deliveries, $orderNum),
                    ArrayHelper::getValue($form->comments, $orderNum, '')
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

        if (!$newOrders) {
            throw new \DomainException("Ни одного заказа не сформировано.");
        }

        // Отправка email продавцам о новом заказе
        foreach ($newOrders as $order) {
            Yii::$app->queue->push(new SendMailJob([
                'view' => 'order/new-order-received',
                'params' => ['order' => $order],
                'subject' => '[' . Yii::$app->params['siteName'] . '] Заказ № ' . $order->getNumber() . ' "' . substr($order->orderItems[0]->trade->name, 0, 25) . (strlen($order->orderItems[0]->trade->name) > 25 ? '...' : '') . '"',
                'to' => [$order->forCompany->email => $order->forCompany->getFullName()],
            ]));
        }

        if ($userOrder->user_email) {
            Yii::$app->queue->push(new SendMailJob([
                'view' => 'order/new-order-for-user',
                'params' => ['userOrder' => $userOrder],
                'subject' => '[' . Yii::$app->params['siteName'] . '] Заказ № ' . $userOrder->id . ' "' . substr($userOrder->orders[0]->orderItems[0]->trade->name, 0, 25) . (strlen($userOrder->orders[0]->orderItems[0]->trade->name) > 25 ? '...' : '') . '"',
                'to' => [$userOrder->user_email => $userOrder->user_name],
            ]));
        }

        return $userOrder;
    }
}