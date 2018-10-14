<?php

namespace core\repositories\News;


use core\entities\News\NewsCategory;
use yii\web\NotFoundHttpException;

class NewsCategoryRepository
{
    public function get($id): NewsCategory
    {
        if (!$category = NewsCategory::findOne($id)) {
            throw new NotFoundHttpException('Раздел не найден.');
        }
        return $category;
    }

    public function getBySlug($slug): NewsCategory
    {
        if (!$category = NewsCategory::find()->where(['slug' => $slug])->limit(1)->one()) {
            throw new NotFoundHttpException('Раздел не найден.');
        }
        return $category;
    }

    public function save(NewsCategory $category)
    {
        if (!$category->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function remove(NewsCategory $category)
    {
        if (!$category->deleteWithChildren()) {
            throw new \RuntimeException('Ошибка при удалении из базы.');
        }
    }
}