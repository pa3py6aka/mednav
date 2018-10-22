<?php

namespace core\repositories\Expo;


use core\entities\Expo\Expo;
use core\entities\Expo\ExpoTagsAssignment;
use core\entities\StatusesInterface;
use yii\web\NotFoundHttpException;

class ExpoRepository
{
    public function get($id): Expo
    {
        if (!$expo = Expo::findOne($id)) {
            throw new NotFoundHttpException('Выставка не найдена');
        }
        return $expo;
    }

    public function findTagAssignment($brandId, $tagId): ?ExpoTagsAssignment
    {
        return ExpoTagsAssignment::find()->where(['exposition_id' => $brandId, 'tag_id' => $tagId])->limit(1)->one();
    }

    public function saveTagAssignment(ExpoTagsAssignment $assignment): void
    {
        if (!$assignment->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function save(Expo $expo): void
    {
        if (!$expo->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function safeRemove(Expo $expo): void
    {
        $expo->setStatus(StatusesInterface::STATUS_DELETED);
        if (!$expo->save()) {
            throw new \RuntimeException('Ошибка при удалении из базы.');
        }
    }

    public function remove(Expo $expo): void
    {
        if (!$expo->delete()) {
            throw new \RuntimeException('Ошибка при удалении из базы.');
        }
    }

    public function massRemove(array $ids, $hardRemove = false): int
    {
        if ($hardRemove) {
            return Expo::deleteAll(['id' => $ids]);
        } else {
            return Expo::updateAll(['status' => StatusesInterface::STATUS_DELETED], ['id' => $ids]);
        }
    }
}