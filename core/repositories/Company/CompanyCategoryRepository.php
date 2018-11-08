<?php

namespace core\repositories\Company;


use core\entities\Company\CompanyCategory;
use core\entities\Company\CompanyCategoryRegion;
use yii\web\NotFoundHttpException;

class CompanyCategoryRepository
{
    public function get($id): CompanyCategory
    {
        if (!$category = CompanyCategory::findOne($id)) {
            throw new NotFoundHttpException('Раздел не найден.');
        }
        return $category;
    }

    public function getBySlug($slug): CompanyCategory
    {
        if (!$category = CompanyCategory::find()->where(['slug' => $slug])->limit(1)->one()) {
            throw new NotFoundHttpException('Раздел не найден.');
        }
        return $category;
    }

    public function clearAttachedRegions($categoryId): void
    {
        CompanyCategoryRegion::deleteAll(['category_id' => $categoryId]);
    }

    public function getRegion($categoryId, $regionId): ?CompanyCategoryRegion
    {
        return CompanyCategoryRegion::find()->where(['category_id' => $categoryId, 'geo_id' => $regionId])->limit(1)->one();
    }

    public function saveRegion(CompanyCategoryRegion $region): void
    {
        if (!$region->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function save(CompanyCategory $category)
    {
        if (!$category->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function remove(CompanyCategory $category)
    {
        if (!$category->deleteWithChildren()) {
            throw new \RuntimeException('Ошибка при удалении из базы.');
        }
    }
}