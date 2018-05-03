<?php

namespace core\repositories\Board;


use core\entities\Board\BoardPhoto;
use core\repositories\NotFoundException;

class BoardPhotoRepository
{
    public function get($id): BoardPhoto
    {
        if (!$photo = BoardPhoto::findOne($id)) {
            throw new NotFoundException('Фото не найдено.');
        }
        return $photo;
    }

    public function save(BoardPhoto $photo): void
    {
        if (!$photo->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function remove(BoardPhoto $photo): void
    {
        if (!$photo->delete()) {
            throw new \RuntimeException('Ошибка при удалении из базы.');
        }
    }
}