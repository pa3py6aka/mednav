<?php

namespace core\readModels\Trade;


use core\components\Settings;
use core\entities\Company\CompanyDeliveryRegion;
use core\entities\Trade\Trade;
use core\entities\Trade\TradeCategory;
use core\entities\Geo;
use core\entities\Trade\TradeUserCategory;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\DataProviderInterface;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class TradeReadRepository
{
    public function get($id): Trade
    {
        if (!$trade = Trade::find()->where(['id' => $id])->limit(1)->one()) {
            throw new NotFoundHttpException("Товар не найден");
        }
        return $trade;
    }

    public function getByIdAndSlug($id, $slug): Trade
    {
        if (!$trade = Trade::find()->where(['id' => $id, 'slug' => $slug])->with('user.company.deliveries.delivery')->limit(1)->one()) {
            throw new NotFoundHttpException("Товар не найден");
        }
        return $trade;
    }

    public function getAllByFilter(TradeCategory $category = null, Geo $geo = null, $companyId = null, $search = null): DataProviderInterface
    {
        $query = Trade::find()
            ->alias('t')
            ->active('t')
            ->with('mainPhoto', 'geo', 'userCategory.currency', 'userCategory.uom', 'category', 'user.company.deliveryRegions');

        if ($category) {
            $ids = ArrayHelper::merge([$category->id], $category->getDescendants()->select('id')->column());
            $query->andWhere(['t.category_id' => $ids]);
        }

        if ($search) {
            $query->andWhere(['like', 't.name', $search]);
        }

        if ($geo) {
            $ids = ArrayHelper::merge([$geo->id], $geo->getDescendants()->select('id')->column());
            $companyIds = CompanyDeliveryRegion::find()
                ->select('company_id')
                ->where(['geo_id' => $ids])
                ->column();
            $query->andWhere([
                'or',
                ['t.geo_id' => $ids],
                ['t.company_id' => $companyIds]
            ]);
            $query->addOrderBy(new Expression('case when t.geo_id in(' . implode(',', $ids) . ') then -1 else 1 end asc'));
        }

        if ($companyId) {
            $query->andWhere(['t.company_id' => $companyId]);
        }

        $query->groupBy('t.id');
        return $this->getProvider($query);
    }

    public function getUserTrades($userId, $status, $userCategoryId = null): DataProviderInterface
    {
        $query = Trade::find()
            ->alias('t')
            ->where(['t.user_id' => $userId]);

        if ($userCategoryId) {
            $query->andWhere(['user_category_id' => $userCategoryId]);
        }

        switch ($status) {
            case 'notDeleted':
                $query->andWhere(['<>', 't.status', Trade::STATUS_DELETED]);
                break;
            case Trade::STATUS_ON_PREMODERATION:
                $query->onModeration('t');
                break;
            case Trade::STATUS_ACTIVE:
                $query->active('t');
                break;
            case Trade::STATUS_DELETED:
                $query->deleted('t');echo $status;exit;
                break;
            default: null;
        }
        return $this->getProvider($query, 25);
    }

    public function getUserCategories($userId, $active = true): ActiveDataProvider
    {
        $query = TradeUserCategory::find()
            ->with($active ? 'activeTrades' : 'trades', 'category')
            ->where(['user_id' => $userId]);

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

    private function getProvider(ActiveQuery $query, $pageSize = null): ActiveDataProvider
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
                'pageSizeLimit' => [1, 250],
                'defaultPageSize' => $pageSize ?: Yii::$app->settings->get(Settings::TRADE_PAGE_SIZE),
                'forcePageParam' => false,
            ]
        ]);
        $provider->models; // Для обновления данных в пагинации
        return $provider;
    }
}