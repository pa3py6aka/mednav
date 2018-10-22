<?php

namespace core\repositories\Expo;


use core\entities\Expo\ExpoCategory;
use yii\web\NotFoundHttpException;

class ExpoCategoryRepository
{
    public function get($id): ExpoCategory
    {
        if (!$category = ExpoCategory::findOne($id)) {
            throw new NotFoundHttpException('Раздел не найден.');
        }
        return $category;
    }

    public function getBySlug($slug): ExpoCategory
    {
        if (!$category = ExpoCategory::find()->where(['slug' => $slug])->limit(1)->one()) {
            throw new NotFoundHttpException('Раздел не найден.');
        }
        return $category;
    }

    public function save(ExpoCategory $category)
    {
        if (!$category->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function remove(ExpoCategory $category)
    {
        if (!$category->deleteWithChildren()) {
            throw new \RuntimeException('Ошибка при удалении из базы.');
        }
    }
}