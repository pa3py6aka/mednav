<?php

namespace core\helpers;


use core\entities\Order\Order;
use core\entities\User\User;

class OrderHelper
{
    public static function getNewOrdersCount(User $user): string
    {
        if ($user->isCompanyActive()) {
            $count = Order::find()->where(['for_company_id' => $user->company->id, 'status' => Order::STATUS_NEW])
                ->andWhere(['<>', 'user_id', $user->id])
                ->count();
            if ($count) {
                return ' <span class="label label-danger label-as-badge">' . $count . '</span>';
            }
        }
        return '';
    }


    public static function newLabel(Order $order): bool
    {
        $user = \Yii::$app->user->identity;
        if ($order->status == Order::STATUS_NEW && $order->user_id != $user->id) {
            return ' <span class="label label-danger label-as-badge">Новый</span>';
        }
        return '';
    }
}