<?php

namespace core\repositories\Trade;


use core\entities\Trade\Trade;
use core\entities\Trade\TradeTagAssignment;
use core\entities\Trade\TradeUserCategory;
use core\repositories\NotFoundException;

class TradeRepository
{
    public function get($id): Trade
    {
        if (!$trade = Trade::findOne($id)) {
            throw new NotFoundException('Товар не найден.');
        }
        return $trade;
    }

    public function getUserCategory($id): TradeUserCategory
    {
        if (!$category = TradeUserCategory::findOne($id)) {
            throw new NotFoundException('Категория не найдена.');
        }
        return $category;
    }

    public function save(Trade $trade): void
    {
        if (!$trade->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function saveTagAssignment(TradeTagAssignment $assignment): void
    {
        if (!$assignment->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function saveUserCategory(TradeUserCategory $userCategory): void
    {
        if (!$userCategory->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function safeRemove(Trade $trade): void
    {
        $trade->setStatus(Trade::STATUS_DELETED);
        if (!$trade->save()) {
            throw new \RuntimeException('Ошибка при удалении из базы.');
        }
    }

    public function remove(Trade $trade): void
    {
        if (!$trade->delete()) {
            throw new \RuntimeException('Ошибка при удалении из базы.');
        }
    }

    public function massRemove(array $ids, $hardRemove = false): int
    {
        if ($hardRemove) {
            return Trade::deleteAll(['id' => $ids]);
        } else {
            return Trade::updateAll(['status' => Trade::STATUS_DELETED], ['id' => $ids]);
        }
    }

    public function removeUserCategory(TradeUserCategory $userCategory): void
    {
        if (!$userCategory->delete()) {
            throw new \RuntimeException('Ошибка при удалении из базы.');
        }
    }
}