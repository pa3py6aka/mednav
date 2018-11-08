<?php

namespace backend\helpers;


use core\entities\CategoryInterface;
use yii\db\ActiveQuery;

class SearchFormHelper
{
    public static function categoryFilter($categoryId, CategoryInterface $category, ActiveQuery $query): void
    {
        if ($categoryId) {
            $category = $category::find()->where(['id' => $categoryId])->limit(1)->one();
            $categoryIds = $category->getDescendants()->select('id')->column();
            $categoryIds[] = $categoryId;
            $query->andFilterWhere(['t.category_id' => $categoryIds]);
        }
    }
}