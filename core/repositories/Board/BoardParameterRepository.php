<?php

namespace core\repositories\Board;


use core\entities\Board\BoardParameter;
use core\entities\Board\BoardParameterOption;
use core\repositories\NotFoundException;

class BoardParameterRepository
{
    public function get($id): BoardParameter
    {
        if (!$parameter = BoardParameter::findOne($id)) {
            throw new NotFoundException('Параметр не найден.');
        }
        return $parameter;
    }

    public function save(BoardParameter $parameter): void
    {
        if (!$parameter->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function remove(BoardParameter $parameter): void
    {
        if (!$parameter->delete()) {
            throw new \RuntimeException('Ошибка при удалении из базы.');
        }
    }

    public function getOption($id): BoardParameterOption
    {
        if (!$option = BoardParameterOption::findOne($id)) {
            throw new NotFoundException('Опция не найдена.');
        }
        return $option;
    }

    public function saveOption(BoardParameterOption $option): void
    {
        if (!$option->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function removeOption(BoardParameterOption $option): void
    {
        if (!$option->delete()) {
            throw new \RuntimeException('Ошибка при удалении из базы.');
        }
    }
}