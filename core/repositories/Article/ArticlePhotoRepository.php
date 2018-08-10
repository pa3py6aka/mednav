<?php

namespace core\repositories\Article;


use core\entities\Article\ArticlePhoto;
use core\repositories\NotFoundException;

class ArticlePhotoRepository
{
    public function get($id): ArticlePhoto
    {
        if (!$photo = ArticlePhoto::findOne($id)) {
            throw new NotFoundException('Фото не найдено.');
        }
        return $photo;
    }

    public function save(ArticlePhoto $photo): void
    {
        if (!$photo->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function remove(ArticlePhoto $photo): void
    {
        if (!$photo->delete()) {
            throw new \RuntimeException('Ошибка при удалении из базы.');
        }
    }
}