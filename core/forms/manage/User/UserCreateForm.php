<?php

namespace core\forms\manage\User;


use core\entities\User\User;
use yii\base\Model;

class UserCreateForm extends Model
{
    public $type;
    public $email;
    public $password;
    public $role;

    public function rules()
    {
        return [
            [['type', 'email', 'password', 'role'], 'required'],
            ['type', 'in', 'range' => array_keys(User::getTypesArray())],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => User::class],
        ];
    }
}