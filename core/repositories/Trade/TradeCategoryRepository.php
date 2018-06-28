<?php

namespace core\repositories\Trade;


use core\entities\Trade\TradeCategory;
use core\entities\Trade\TradeCategoryRegion;
use core\repositories\NotFoundException;

class TradeCategoryRepository
{
    public function get($id): TradeCategory
    {
        if (!$category = TradeCategory::findOne($id)) {
            throw new NotFoundException('Раздел не найден.');
        }
        return $category;
    }

    public function getBySlug($slug): TradeCategory
    {
        if (!$category = TradeCategory::find()->where(['slug' => $slug])->limit(1)->one()) {
            throw new NotFoundException('Раздел не найден.');
        }
        return $category;
    }

    public function clearAttachedRegions($categoryId): void
    {
        TradeCategoryRegion::deleteAll(['category_id' => $categoryId]);
    }

    public function getRegion($categoryId, $regionId): ?TradeCategoryRegion
    {
        return TradeCategoryRegion::find()->where(['category_id' => $categoryId, 'geo_id' => $regionId])->limit(1)->one();
    }

    public function saveRegion(TradeCategoryRegion $region): void
    {
        if (!$region->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function save(TradeCategory $category)
    {
        if (!$category->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function remove(TradeCategory $category)
    {
        if (!$category->deleteWithChildren()) {
            throw new \RuntimeException('Ошибка при удалении из базы.');
        }
    }
}