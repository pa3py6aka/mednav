<?php

namespace core\repositories\Article;


use core\entities\Article\ArticleCategory;
use core\repositories\NotFoundException;

class ArticleCategoryRepository
{
    public function get($id): ArticleCategory
    {
        if (!$category = ArticleCategory::findOne($id)) {
            throw new NotFoundException('Раздел не найден.');
        }
        return $category;
    }

    public function getBySlug($slug): ArticleCategory
    {
        if (!$category = ArticleCategory::find()->where(['slug' => $slug])->limit(1)->one()) {
            throw new NotFoundException('Раздел не найден.');
        }
        return $category;
    }

    public function save(ArticleCategory $category)
    {
        if (!$category->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function remove(ArticleCategory $category)
    {
        if (!$category->deleteWithChildren()) {
            throw new \RuntimeException('Ошибка при удалении из базы.');
        }
    }
}