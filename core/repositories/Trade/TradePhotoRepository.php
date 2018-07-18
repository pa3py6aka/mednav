<?php

namespace core\repositories\Trade;


use core\entities\Trade\TradePhoto;
use core\repositories\NotFoundException;

class TradePhotoRepository
{
    public function get($id): TradePhoto
    {
        if (!$photo = TradePhoto::findOne($id)) {
            throw new NotFoundException('Фото не найдено.');
        }
        return $photo;
    }

    public function save(TradePhoto $photo): void
    {
        if (!$photo->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function remove(TradePhoto $photo): void
    {
        if (!$photo->delete()) {
            throw new \RuntimeException('Ошибка при удалении из базы.');
        }
    }
}