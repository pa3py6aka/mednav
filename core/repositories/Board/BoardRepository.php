<?php

namespace core\repositories\Board;


use core\entities\Board\Board;
use core\entities\Board\BoardParameter;
use core\entities\Board\BoardParameterAssignment;
use core\entities\Board\BoardTagAssignment;
use core\repositories\NotFoundException;
use yii\data\ActiveDataProvider;

class BoardRepository
{
    public function get($id): Board
    {
        if (!$board = Board::findOne($id)) {
            throw new NotFoundException('Объявление не найдено.');
        }
        return $board;
    }

    public function findTagAssignment($boardId, $tagId): ?BoardTagAssignment
    {
        return BoardTagAssignment::find()->where(['board_id' => $boardId, 'tag_id' => $tagId])->limit(1)->one();
    }

    public function saveTagAssignment(BoardTagAssignment $assignment): void
    {
        if (!$assignment->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function getParameter($id): BoardParameter
    {
        if (!$parameter = BoardParameter::findOne($id)) {
            throw new NotFoundException('Параметр не найден.');
        }
        return $parameter;
    }

    public function saveParameterAssignment(BoardParameterAssignment $assignment): void
    {
        if (!$assignment->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function save(Board $board): void
    {
        if (!$board->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function safeRemove(Board $board): void
    {
        $board->status = Board::STATUS_DELETED;
        if (!$board->save()) {
            throw new \RuntimeException('Ошибка при удалении из базы.');
        }
    }

    public function remove(Board $board): void
    {
        if (!$board->delete()) {
            throw new \RuntimeException('Ошибка при удалении из базы.');
        }
    }

    public function massRemove(array $ids, $hardRemove = false): int
    {
        if ($hardRemove) {
            return Board::deleteAll(['id' => $ids]);
        } else {
            return Board::updateAll(['status' => Board::STATUS_DELETED], ['id' => $ids]);
        }
    }
}