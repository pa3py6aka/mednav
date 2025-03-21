<?php

namespace core\repositories\Board;


use core\entities\Board\BoardCategory;
use core\entities\Board\BoardCategoryParameter;
use core\entities\Board\BoardCategoryRegion;
use yii\web\NotFoundHttpException;

class BoardCategoryRepository
{
    public function get($id): BoardCategory
    {
        if (!$category = BoardCategory::findOne($id)) {
            throw new NotFoundHttpException('Раздел не найден.');
        }
        return $category;
    }

    public function getBySlug($slug): BoardCategory
    {
        if (!$category = BoardCategory::find()->where(['slug' => $slug])->limit(1)->one()) {
            throw new NotFoundHttpException('Раздел не найден.');
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

    public function getRegionBySlug($category, $region)
    {

    }

    public function saveRegion(BoardCategoryRegion $region): void
    {
        if (!$region->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function saveParameter(BoardCategoryParameter $parameter): void
    {
        if (!$parameter->save()) {
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