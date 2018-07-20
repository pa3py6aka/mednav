<?php

namespace core\readModels\Trade;


use core\entities\Trade\Trade;
use core\entities\Trade\TradeCategory;
use core\entities\Geo;
use core\entities\Trade\TradeUserCategory;
use core\entities\Trade\TradeUserCategoryAssignment;
use yii\data\ActiveDataProvider;
use yii\data\DataProviderInterface;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class TradeReadRepository
{
    public function getByIdAndSlug($id, $slug): Trade
    {
        if (!$trade = Trade::find()->where(['id' => $id, 'slug' => $slug])->with('user.company.deliveries.delivery')->limit(1)->one()) {
            throw new NotFoundHttpException("Товар не найден");
        }
        return $trade;
    }

    public function getAllByFilter(TradeCategory $category = null, Geo $geo = null, $typeParameterOption = null, $userId = null): DataProviderInterface
    {
        $query = Trade::find()
            ->alias('t')
            ->active('t')
            ->with('mainPhoto', 'geo', 'userCategory', 'category', 'user.company');

        if ($category) {
            $ids = ArrayHelper::merge([$category->id], $category->getDescendants()->select('id')->column());
            $query->andWhere(['t.category_id' => $ids]);
        }

        if ($geo) {
            $ids = ArrayHelper::merge([$geo->id], $geo->getDescendants()->select('id')->column());
            $query->andWhere(['t.geo_id' => $ids]);
        }

        if ($userId) {
            $query->andWhere(['t.user_id' => $userId]);
        }

        $query->groupBy('t.id');
        return $this->getProvider($query);
    }

    public function getUserTrades($userId, $status = Trade::STATUS_ACTIVE): DataProviderInterface
    {
        $query = Trade::find()
            ->alias('t')
            ->where(['t.user_id' => $userId]);

        switch ($status) {
            case Trade::STATUS_ON_PREMODERATION:
                $query->onModeration('t');
                break;
            case Trade::STATUS_DELETED:
                $query->deleted('t');
                break;
            default: $query->active('t');
        }
        return $this->getProvider($query);
    }

    public function getUserCategories($userId): ActiveDataProvider
    {
        $query = TradeUserCategory::find()->with('trades', 'category')->where(['user_id' => $userId]);
        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSizeLimit' => [25, 250],
                'defaultPageSize' => 25,
                'forcePageParam' => false,
            ]
        ]);
    }

    public function getUserCategoriesCount($userId): int
    {
        return TradeUserCategory::find()->where(['user_id' => $userId])->count();
    }

    private function getProvider(ActiveQuery $query): ActiveDataProvider
    {
        $provider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
                'attributes' => [
                    'id' => [
                        'asc' => ['t.id' => SORT_ASC],
                        'desc' => ['t.id' => SORT_DESC],
                        'label' => 'Дате',
                    ],
                    'price' => [
                        'asc' => ['-[[t.price]]' => SORT_DESC],
                        'desc' => ['t.price' => SORT_DESC],
                        'label' => 'Цене'
                    ],
                    'stock' => [
                        'asc' => ['t.stock' => SORT_ASC],
                        'desc' => ['t.stock' => SORT_DESC],
                        'label' => 'В наличии',
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