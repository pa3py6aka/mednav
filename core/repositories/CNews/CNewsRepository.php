<?php

namespace core\repositories\CNews;


use core\entities\CNews\CNews;
use core\entities\CNews\CNewsTagsAssignment;
use yii\web\NotFoundHttpException;

class CNewsRepository
{
    public function get($id): CNews
    {
        if (!$cnews = CNews::findOne($id)) {
            throw new NotFoundHttpException('Новость не найдена.');
        }
        return $cnews;
    }

    public function findTagAssignment($cnewsId, $tagId): ?CNewsTagsAssignment
    {
        return CNewsTagsAssignment::find()->where(['cnews_id' => $cnewsId, 'tag_id' => $tagId])->limit(1)->one();
    }

    public function saveTagAssignment(CNewsTagsAssignment $assignment): void
    {
        if (!$assignment->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function save(CNews $cnews): void
    {
        if (!$cnews->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function safeRemove(CNews $cnews): void
    {
        $cnews->setStatus(CNews::STATUS_DELETED);
        if (!$cnews->save()) {
            throw new \RuntimeException('Ошибка при удалении из базы.');
        }
    }

    public function remove(CNews $cnews): void
    {
        if (!$cnews->delete()) {
            throw new \RuntimeException('Ошибка при удалении из базы.');
        }
    }

    public function massRemove(array $ids, $hardRemove = false): int
    {
        if ($hardRemove) {
            return CNews::deleteAll(['id' => $ids]);
        } else {
            return CNews::updateAll(['status' => CNews::STATUS_DELETED], ['id' => $ids]);
        }
    }
}