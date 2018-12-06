<?php

namespace core\repositories\Article;


use core\entities\Article\Article;
use core\entities\Article\ArticleTagsAssignment;
use yii\web\NotFoundHttpException;

class ArticleRepository
{
    public function get($id): Article
    {
        if (!$company = Article::findOne($id)) {
            throw new NotFoundHttpException('Статья не найдена.');
        }
        return $company;
    }

    public function findTagAssignment($articleId, $tagId): ?ArticleTagsAssignment
    {
        return ArticleTagsAssignment::find()->where(['article_id' => $articleId, 'tag_id' => $tagId])->limit(1)->one();
    }

    public function saveTagAssignment(ArticleTagsAssignment $assignment): void
    {
        if (!$assignment->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function save(Article $article): void
    {
        if (!$article->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function safeRemove(Article $article): void
    {
        $article->setStatus(Article::STATUS_DELETED);
        if (!$article->save()) {
            throw new \RuntimeException('Ошибка при удалении из базы.');
        }
    }

    public function remove(Article $article): void
    {
        if (!$article->delete()) {
            throw new \RuntimeException('Ошибка при удалении из базы.');
        }
    }

    public function massRemove(array $ids, $hardRemove = false): int
    {
        if ($hardRemove) {
            return Article::deleteAll(['id' => $ids]);
        }
        return Article::updateAll(['status' => Article::STATUS_DELETED], ['id' => $ids]);
    }
}