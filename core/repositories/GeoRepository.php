<?php

namespace core\repositories;


use core\entities\Geo;

class GeoRepository
{
    public function get($id): Geo
    {
        if (!$geo = Geo::findOne($id)) {
            throw new NotFoundException('Регион не найден.');
        }
        return $geo;
    }

    public function getBySlug($slug): Geo
    {
        if (!$geo = Geo::find()->where(['slug' => $slug])->limit(1)->one()) {
            throw new NotFoundException('Регион не найден.');
        }
        return $geo;
    }

    public function save(Geo $geo)
    {
        if (!$geo->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function remove(Geo $geo)
    {
        if (!$geo->delete()) {
            throw new \RuntimeException('Ошибка при удалении из базы.');
        }
    }
}