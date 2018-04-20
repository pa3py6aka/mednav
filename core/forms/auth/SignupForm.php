<?php

namespace core\forms\auth;


use core\entities\User\User;
use yii\base\Model;

class SignupForm extends Model
{
    public $type;
    public $email;
    public $password;
    public $repeatPassword;
    public $captcha;
    public $agreement;

    public function rules()
    {
        return [
            [['type', 'email', 'password'], 'required'],
            ['type', 'in', 'range' => array_keys(User::getTypesArray())],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => User::class],
            ['password', 'string', 'min' => 6],
            ['repeatPassword', 'required', 'message' => 'Повторите пароль'],
            ['repeatPassword', 'compare', 'compareAttribute' => 'password', 'message' => 'Пароли не совпадают'],
            ['captcha', 'required', 'message' => 'Укажите проверочный код'],
            ['captcha', 'captcha', 'captchaAction' => '/auth/captcha'],
            ['agreement', 'required', 'requiredValue' => 1, 'message' => 'Вы должны подтвердить своё согласие'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'type' => 'Тип профиля',
            'email' => 'E-mail',
            'password' => 'Пароль',
            'repeatPassword' => 'Повторите',
            'agreement' => 'Соглашение',
        ];
    }

}