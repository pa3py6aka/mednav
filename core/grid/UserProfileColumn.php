<?php

namespace core\grid;


use core\entities\User\User;
use core\entities\UserOwnerInterface;
use yii\grid\DataColumn;

class UserProfileColumn extends DataColumn
{
    public $attribute = 'userType';
    public $label = 'Профиль';

    public function init()
    {
        $this->filter = User::getTypesArray();
        parent::init();
    }

    public function renderDataCellContent($model, $key, $index)
    {
        /* @var $model UserOwnerInterface */
        return $model->getOwnerUser()->typeName;
    }
}