<?php

namespace core\repositories\CNews;


use core\entities\CNews\CNewsPhoto;
use yii\web\NotFoundHttpException;

class CNewsPhotoRepository
{
    public function get($id): CNewsPhoto
    {
        if (!$photo = CNewsPhoto::findOne($id)) {
            throw new NotFoundHttpException('Фото не найдено.');
        }
        return $photo;
    }

    public function save(CNewsPhoto $photo): void
    {
        if (!$photo->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function remove(CNewsPhoto $photo): void
    {
        if (!$photo->delete()) {
            throw new \RuntimeException('Ошибка при удалении из базы.');
        }
    }
}