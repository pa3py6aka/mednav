<?php

namespace core\readModels;


use core\entities\Order\Order;
use core\entities\User\User;
use yii\data\ActiveDataProvider;
use yii\data\DataProviderInterface;
use yii\db\ActiveQuery;

class OrderReadRepository
{
    public function getOrdersFor(User $user): DataProviderInterface
    {
        $query = Order::find()->where(['user_id' => $user->id]);
        if ($user->company) {
            $query->orWhere(['for_company_id' => $user->company->id]);
        }
        return $this->getProvider($query);
    }

    public function getOrderItems(Order $order): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => $order->getOrderItems()->with('trade'),
            'pagination' => false,
        ]);
    }

    private function getProvider(ActiveQuery $query): ActiveDataProvider
    {
        $provider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC],
                'attributes' => [
                    'created_at' => [
                        'asc' => ['id' => SORT_ASC],
                        'desc' => ['id' => SORT_DESC],
                        'label' => 'Дата',
                    ],
                ],
            ],
            'pagination' => [
                'pageSizeLimit' => [25, 250],
                'defaultPageSize' => 25,
                'forcePageParam' => false,
            ]
        ]);
        return $provider;
    }
}