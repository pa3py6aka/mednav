<?php

namespace core\repositories\News;


use core\entities\News\News;
use core\entities\News\NewsTagsAssignment;
use core\repositories\NotFoundException;

class NewsRepository
{
    public function get($id): News
    {
        if (!$company = News::findOne($id)) {
            throw new NotFoundException('Новость не найдена.');
        }
        return $company;
    }

    public function findTagAssignment($articleId, $tagId): ?NewsTagsAssignment
    {
        return NewsTagsAssignment::find()->where(['news_id' => $articleId, 'tag_id' => $tagId])->limit(1)->one();
    }

    public function saveTagAssignment(NewsTagsAssignment $assignment): void
    {
        if (!$assignment->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function save(News $article): void
    {
        if (!$article->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function safeRemove(News $article): void
    {
        $article->setStatus(News::STATUS_DELETED);
        if (!$article->save()) {
            throw new \RuntimeException('Ошибка при удалении из базы.');
        }
    }

    public function remove(News $article): void
    {
        if (!$article->delete()) {
            throw new \RuntimeException('Ошибка при удалении из базы.');
        }
    }

    public function massRemove(array $ids, $hardRemove = false): int
    {
        if ($hardRemove) {
            return News::deleteAll(['id' => $ids]);
        } else {
            return News::updateAll(['status' => News::STATUS_DELETED], ['id' => $ids]);
        }
    }
}