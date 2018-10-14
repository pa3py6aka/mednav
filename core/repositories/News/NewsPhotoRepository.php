<?php

namespace core\repositories\News;


use core\entities\News\NewsPhoto;
use core\repositories\NotFoundException;

class NewsPhotoRepository
{
    public function get($id): NewsPhoto
    {
        if (!$photo = NewsPhoto::findOne($id)) {
            throw new NotFoundException('Фото не найдено.');
        }
        return $photo;
    }

    public function save(NewsPhoto $photo): void
    {
        if (!$photo->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function remove(NewsPhoto $photo): void
    {
        if (!$photo->delete()) {
            throw new \RuntimeException('Ошибка при удалении из базы.');
        }
    }
}