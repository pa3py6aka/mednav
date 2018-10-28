<?php

namespace core\repositories;


use core\entities\Order\Order;
use core\entities\Order\OrderItem;
use core\entities\Order\UserOrder;
use yii\web\NotFoundHttpException;

class OrderRepository
{
    public function get($id): Order
    {
        if (!$order = Order::findOne($id)) {
            throw new NotFoundHttpException('Заказ не найден.');
        }
        return $order;
    }

    public function getFullOrder($id): UserOrder
    {
        if (!$fullOrder = UserOrder::findOne($id)) {
            throw new NotFoundHttpException('Заказ не найден.');
        }
        return $fullOrder;
    }

    public function save(Order $order): void
    {
        if (!$order->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function saveUserOrder(UserOrder $userOrder): void
    {
        if (!$userOrder->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function saveItem(OrderItem $item): void
    {
        if (!$item->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function remove(Order $order): void
    {
        if (!$order->delete()) {
            throw new \RuntimeException('Ошибка при удалении из базы.');
        }
    }
}