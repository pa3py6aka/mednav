<?php

namespace core\readModels\Board;


use core\entities\Board\Board;
use core\entities\Board\BoardCategory;
use core\entities\Geo;
use yii\data\ActiveDataProvider;
use yii\data\DataProviderInterface;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class BoardReadRepository
{
    public function getByIdAndSlug($id, $slug): Board
    {
        if (!$board = Board::find()->where(['id' => $id, 'slug' => $slug])->limit(1)->one()) {
            throw new NotFoundHttpException("Объявление не найдено");
        }
        return $board;
    }

    public function getAllByFilter(BoardCategory $category = null, Geo $geo = null, $typeParameterOption = null): DataProviderInterface
    {
        $query = Board::find()->alias('b')->active('b')->with('mainPhoto', 'category', 'geo', 'typeBoardParameter.option');

        if ($category) {
            $ids = ArrayHelper::merge([$category->id], $category->getDescendants()->select('id')->column());
            $query->andWhere(['b.category_id' => $ids]);
        }

        if ($geo) {
            $ids = ArrayHelper::merge([$geo->id], $geo->getDescendants()->select('id')->column());
            $query->andWhere(['b.geo_id' => $ids]);
        }

        if ($typeParameterOption) {
            $query->joinWith('typeBoardParameter tp', false);
            $query->andWhere(['tp.option_id' => $typeParameterOption]);
        }

        $query->groupBy('b.id');
        return $this->getProvider($query);
    }

    public function getUserBoards($userId, $status = Board::STATUS_ACTIVE): DataProviderInterface
    {
        $query = Board::find()
            ->alias('b')
            ->where(['b.author_id' => $userId])
            ->with('category', 'typeBoardParameter.option');

        switch ($status) {
            case Board::STATUS_ARCHIVE:
                $query->archive('b');
                break;
            case Board::STATUS_ON_MODERATION:
                $query->onModeration('b');
                break;
            default: $query->active('b');
        }
        return $this->getProvider($query);
    }

    public function toExtendCount($userId): int
    {
        return Board::find()
            ->where(['author_id' => $userId])
            ->toExtend()
            ->count();
    }

    public function toExtendIds($userId): array
    {
        return Board::find()
            ->where(['author_id' => $userId])
            ->toExtend()
            ->select('id')
            ->column();
    }

    private function getProvider(ActiveQuery $query): ActiveDataProvider
    {
        $provider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['date' => SORT_DESC],
                'attributes' => [
                    'id' => [
                        'asc' => ['b.id' => SORT_ASC],
                        'desc' => ['b.id' => SORT_DESC],
                    ],
                    'price' => [
                        'asc' => ['-[[b.price]]' => SORT_DESC],
                        'desc' => ['b.price' => SORT_DESC],
                        'label' => 'Цена'
                    ],
                    'date' => [
                        'asc' => ['b.updated_at' => SORT_ASC],
                        'desc' => ['b.updated_at' => SORT_DESC],
                        'label' => 'Дата',
                    ],
                ],
            ],
            'pagination' => [
                'pageSizeLimit' => [5, 250], //Todo Выставить 25 на продакшине
                'defaultPageSize' => 5,
                'forcePageParam' => false,
            ]
        ]);
        $provider->models; // Для обновления данных в пагинации
        return $provider;
    }
}