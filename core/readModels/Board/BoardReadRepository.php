<?php

namespace core\readModels\Board;


use core\entities\Board\Board;
use core\entities\Board\BoardCategory;
use core\entities\Geo;
use yii\data\ActiveDataProvider;
use yii\data\DataProviderInterface;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

class BoardReadRepository
{
    public function getAllByFilter(BoardCategory $category = null, Geo $geo = null, $typeParameterOption = null): DataProviderInterface
    {
        $query = Board::find()->alias('b')->active('b')->with('mainPhoto', 'category', 'geo', 'typeBoardParameter.option');

        if ($category) {
            $ids = ArrayHelper::merge([$category->id], $category->getLeaves()->select('id')->column());
            $query->andWhere(['b.category_id' => $ids]);
        }

        if ($geo) {
            $ids = ArrayHelper::merge([$geo->id], $geo->getLeaves()->select('id')->column());
            $query->andWhere(['b.geo_id' => $ids]);
        }

        if ($typeParameterOption) {
            $query->joinWith('typeBoardParameter tp', false);
            $query->andWhere(['tp.option_id' => $typeParameterOption]);
        }

        $query->groupBy('b.id');
        return $this->getProvider($query);
    }

    private function getProvider(ActiveQuery $query): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['date' => SORT_DESC],
                'attributes' => [
                    'id' => [
                        'asc' => ['b.id' => SORT_ASC],
                        'desc' => ['b.id' => SORT_DESC],
                    ],
                    'price' => [
                        'asc' => ['b.price' => SORT_ASC],
                        'desc' => ['b.price' => SORT_DESC],
                    ],
                    'date' => [
                        'asc' => ['b.updated_at' => SORT_ASC],
                        'desc' => ['b.updated_at' => SORT_DESC],
                    ],
                ],
            ],
            'pagination' => [
                'pageSizeLimit' => [2, 250],
                'defaultPageSize' => 2
            ]
        ]);
    }
}