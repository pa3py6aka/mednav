<?php

namespace core\repositories\CNews;


use core\entities\CNews\CNewsCategory;
use yii\web\NotFoundHttpException;

class CNewsCategoryRepository
{
    public function get($id): CNewsCategory
    {
        if (!$category = CNewsCategory::findOne($id)) {
            throw new NotFoundHttpException('Раздел не найден.');
        }
        return $category;
    }

    public function getBySlug($slug): CNewsCategory
    {
        if (!$category = CNewsCategory::find()->where(['slug' => $slug])->limit(1)->one()) {
            throw new NotFoundHttpException('Раздел не найден.');
        }
        return $category;
    }

    public function save(CNewsCategory $category)
    {
        if (!$category->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function remove(CNewsCategory $category)
    {
        if (!$category->deleteWithChildren()) {
            throw new \RuntimeException('Ошибка при удалении из базы.');
        }
    }
}