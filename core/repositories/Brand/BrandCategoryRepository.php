<?php

namespace core\repositories\Brand;


use core\entities\Brand\BrandCategory;
use yii\web\NotFoundHttpException;

class BrandCategoryRepository
{
    public function get($id): BrandCategory
    {
        if (!$category = BrandCategory::findOne($id)) {
            throw new NotFoundHttpException('Раздел не найден.');
        }
        return $category;
    }

    public function getBySlug($slug): BrandCategory
    {
        if (!$category = BrandCategory::find()->where(['slug' => $slug])->limit(1)->one()) {
            throw new NotFoundHttpException('Раздел не найден.');
        }
        return $category;
    }

    public function save(BrandCategory $category)
    {
        if (!$category->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function remove(BrandCategory $category)
    {
        if (!$category->deleteWithChildren()) {
            throw new \RuntimeException('Ошибка при удалении из базы.');
        }
    }
}