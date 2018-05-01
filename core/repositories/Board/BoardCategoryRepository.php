<?php

namespace core\repositories\Board;


use core\entities\Board\BoardCategory;
use core\entities\Board\BoardCategoryRegion;
use core\repositories\NotFoundException;

class BoardCategoryRepository
{
    public function get($id): BoardCategory
    {
        if (!$category = BoardCategory::findOne($id)) {
            throw new NotFoundException('Раздел не найден.');
        }
        return $category;
    }

    public function getBySlug($slug): BoardCategory
    {
        if (!$category = BoardCategory::find()->where(['slug' => $slug])->limit(1)->one()) {
            throw new NotFoundException('Раздел не найден.');
        }
        return $category;
    }

    public function clearAttachedRegions($categoryId): void
    {
        BoardCategoryRegion::deleteAll(['category_id' => $categoryId]);
    }

    public function getRegion($categoryId, $regionId): ?BoardCategoryRegion
    {
        return BoardCategoryRegion::find()->where(['category_id' => $categoryId, 'geo_id' => $regionId])->limit(1)->one();
    }

    public function saveRegion(BoardCategoryRegion $region): void
    {
        if (!$region->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function save(BoardCategory $category)
    {
        if (!$category->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function remove(BoardCategory $category)
    {
        if (!$category->deleteWithChildren()) {
            throw new \RuntimeException('Ошибка при удалении из базы.');
        }
    }
}