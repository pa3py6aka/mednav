<?php

namespace core\useCases\auth;


use core\entities\User\User;
use core\forms\auth\LoginForm;
use core\repositories\UserRepository;

class AuthService
{
    private $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function auth(LoginForm $form): User
    {
        $user = $this->users->findByEmail($form->email);
        if (!$user || !$user->validatePassword($form->password)) {
            throw new \DomainException('Неверный e-mail и/или пароль.');
        }
        if ($user->isWait()) {
            throw new \DomainException('Ваш e-mail ещё не подтверждён.');
        }
        if ($user->isOnModeration()) {
            throw new \DomainException('Данный профиль находится на проверке.<br>Как только администратор активирует профиль Вам будет направлено уведомление на почту');
        }
        if ($user->isDeleted()) {
            throw new \DomainException('Ваш профиль заблокирован.');
        }
        return $user;
    }
}