<?php

namespace core\forms\manage\User;


use core\entities\User\User;
use Yii;
use yii\base\Model;

class UserEditForm extends Model
{
    public $type;
    public $email;
    public $password;
    public $role;

    private $_user;

    public function __construct(User $user, array $config = [])
    {
        $this->_user = $user;
        $this->type = $user->type;
        $this->email = $user->email;
        $this->role = current(Yii::$app->authManager->getRolesByUser($user->id))->name;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['type', 'email', 'role'], 'required'],
            ['type', 'in', 'range' => array_keys(User::getTypesArray())],
            ['password', 'string'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => User::class, 'filter' => ['<>', 'id', $this->_user->id]],
        ];
    }

    public function attributeLabels()
    {
        return [
            'type' => 'Тип',
            'email' => 'E-mail',
            'password' => 'Пароль',
            'role' => 'Роль',
        ];
    }
}