<?php

namespace core\repositories\Expo;


use core\entities\Expo\ExpoPhoto;
use yii\web\NotFoundHttpException;

class ExpoPhotoRepository
{
    public function get($id): ExpoPhoto
    {
        if (!$photo = ExpoPhoto::findOne($id)) {
            throw new NotFoundHttpException('Фото не найдено.');
        }
        return $photo;
    }

    public function save(ExpoPhoto $photo): void
    {
        if (!$photo->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function remove(ExpoPhoto $photo): void
    {
        if (!$photo->delete()) {
            throw new \RuntimeException('Ошибка при удалении из базы.');
        }
    }
}