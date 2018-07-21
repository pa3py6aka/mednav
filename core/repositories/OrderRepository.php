<?php

namespace core\repositories;


use core\entities\Company\CompanyDelivery;
use core\entities\Order\Order;
use core\entities\Order\OrderItem;
use core\repositories\NotFoundException;

class OrderRepository
{
    public function get($id): Order
    {
        if (!$order = Order::findOne($id)) {
            throw new NotFoundException('Заказ не найден.');
        }
        return $order;
    }

    public function save(Order $order): void
    {
        if (!$order->save()) {
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