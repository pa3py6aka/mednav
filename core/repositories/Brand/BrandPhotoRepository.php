<?php

namespace core\repositories\Brand;


use core\entities\Brand\BrandPhoto;
use yii\web\NotFoundHttpException;

class BrandPhotoRepository
{
    public function get($id): BrandPhoto
    {
        if (!$photo = BrandPhoto::findOne($id)) {
            throw new NotFoundHttpException('Фото не найдено.');
        }
        return $photo;
    }

    public function save(BrandPhoto $photo): void
    {
        if (!$photo->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function remove(BrandPhoto $photo): void
    {
        if (!$photo->delete()) {
            throw new \RuntimeException('Ошибка при удалении из базы.');
        }
    }
}