<?php

namespace core\repositories\Brand;


use core\entities\Brand\Brand;
use core\entities\Brand\BrandTagsAssignment;
use core\entities\StatusesInterface;
use yii\web\NotFoundHttpException;

class BrandRepository
{
    public function get($id): Brand
    {
        if (!$brand = Brand::findOne($id)) {
            throw new NotFoundHttpException('Бренд не найдена');
        }
        return $brand;
    }

    public function findTagAssignment($brandId, $tagId): ?BrandTagsAssignment
    {
        return BrandTagsAssignment::find()->where(['brand_id' => $brandId, 'tag_id' => $tagId])->limit(1)->one();
    }

    public function saveTagAssignment(BrandTagsAssignment $assignment): void
    {
        if (!$assignment->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function save(Brand $brand): void
    {
        if (!$brand->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function safeRemove(Brand $brand): void
    {
        $brand->setStatus(StatusesInterface::STATUS_DELETED);
        if (!$brand->save()) {
            throw new \RuntimeException('Ошибка при удалении из базы.');
        }
    }

    public function remove(Brand $brand): void
    {
        if (!$brand->delete()) {
            throw new \RuntimeException('Ошибка при удалении из базы.');
        }
    }

    public function massRemove(array $ids, $hardRemove = false): int
    {
        if ($hardRemove) {
            return Brand::deleteAll(['id' => $ids]);
        } else {
            return Brand::updateAll(['status' => StatusesInterface::STATUS_DELETED], ['id' => $ids]);
        }
    }
}