<?php

namespace core\repositories\Company;


use core\entities\Company\CompanyPhoto;
use core\repositories\NotFoundException;

class CompanyPhotoRepository
{
    public function get($id): CompanyPhoto
    {
        if (!$photo = CompanyPhoto::findOne($id)) {
            throw new NotFoundException('Фото не найдено.');
        }
        return $photo;
    }

    public function save(CompanyPhoto $photo): void
    {
        if (!$photo->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function remove(CompanyPhoto $photo): void
    {
        if (!$photo->delete()) {
            throw new \RuntimeException('Ошибка при удалении из базы.');
        }
    }
}