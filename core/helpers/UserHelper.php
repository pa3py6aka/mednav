<?php

namespace core\helpers;


use core\entities\User\User;
use yii\helpers\Url;

class UserHelper
{
    public static function fillProfileMessage(User $user): string
    {
        $need = [];
        if (empty($user->name) || empty($user->email) || empty($user->geo_id)) {
            $need[] = 'форму <a href="' . Url::to(['/user/account/profile']) . '">вашего профиля</a>';
        }
        if ($user->isCompany() && !$user->isCompanyActive()) {
            $need[] = '<a href="' . Url::to(['/user/account/company']) . '">данные о компании</a>';
        }
        return implode(' и ', $need);
    }
}