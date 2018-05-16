<?php

namespace frontend\widgets;


use yii\widgets\Breadcrumbs;

class AccountBreadcrumbs
{
    public static function show(array $links = null)
    {
        $allLinks = [];
        if (!$links) {
            $allLinks[] = 'Личный кабинет';
        } else {
            $allLinks[] = ['label' => 'Личный кабинет', 'url' => ['/user/account/index']];
            $allLinks = array_merge($allLinks, $links);
        }

        return Breadcrumbs::widget([
            'links' => $allLinks,
        ]);
    }
}