<?php

namespace core\forms\User;


use core\entities\User\User;
use yii\base\Model;

class UpdatePasswordForm extends Model
{
    public $oldPassword;
    public $password;
    public $repeatPassword;

    private $_user;

    public function __construct(User $user, array $config = [])
    {
        $this->_user = $user;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['oldPassword', 'password', 'repeatPassword'], 'required'],
            ['oldPassword', 'validatePassword'],
            ['password', 'string', 'min' => 6],
            ['repeatPassword', 'compare', 'compareAttribute' => 'password', 'message' => 'Пароли не совпадают'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'oldPassword' => 'Старый пароль',
            'password' => 'Пароль',
            'repeatPassword' => 'Повторите пароль',
        ];
    }

    public function validatePassword($attribute, $params, $validator)
    {
        if (!$this->_user->validatePassword($this->$attribute)) {
            $this->addError($attribute, 'Пароль неверный');
        }
    }
}