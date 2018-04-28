<?php

namespace core\forms\auth;


use core\entities\User\User;
use yii\base\Model;

class PasswordResetRequestForm extends Model
{
    public $email;

    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => User::class,
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => 'Пользователя с таким e-mail не найдено, либо профиль не активирован'
            ],
        ];
    }
}