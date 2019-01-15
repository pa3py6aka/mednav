<?php

namespace core\grid;


use core\entities\User\User;
use core\entities\UserOwnerInterface;
use yii\grid\DataColumn;
use yii\helpers\Html;

class UserNameColumn extends DataColumn
{
    public $attribute = 'user';
    public $label = 'Пользователь';
    public $filterInputOptions = ['class' => 'form-control', 'id' => null, 'placeholder' => 'ID/email/имя/название компании пользователя'];
    public $format = 'raw';

    public function renderDataCellContent($model, $key, $index)
    {
        /* @var $model UserOwnerInterface*/
        $user = $model->getOwnerUser();
        if ($user->isCompany() && $user->isCompanyActive()) {
            return $user->company->id . ' ' . Html::a($user->company->getFullName(), ['/company/company/view', 'id' => $user->company->id], ['target' => '_blank']);
        }

        return $user->id . ' ' . Html::a($user->getUserName(), ['/user/view', 'id' => $user->id], ['target' => '_blank']);
    }
}