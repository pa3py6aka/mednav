<?php

namespace core\useCases\auth;


use core\forms\auth\PasswordResetRequestForm;
use core\forms\auth\ResetPasswordForm;
use core\repositories\UserRepository;
use core\services\Mailer;

class PasswordResetService
{
    private $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function request(PasswordResetRequestForm $form): void
    {
        $user = $this->users->getByEmail($form->email);

        if (!$user->isActive()) {
            throw new \DomainException('Пользователь не активен.');
        }

        $user->requestPasswordReset();
        $this->users->save($user);

        Mailer::send(
            $user->email,
            \Yii::$app->name .': Сброс пароля',
            'auth/reset-password',
            ['user' => $user]
        );
    }

    public function validateToken($token): void
    {
        if (empty($token) || !is_string($token)) {
            throw new \DomainException('Токен не может быть пустым.');
        }
        if (!$this->users->existsByPasswordResetToken($token)) {
            throw new \DomainException('Неверный токен!');
        }
    }

    public function reset(string $token, ResetPasswordForm $form): void
    {
        $user = $this->users->getByPasswordResetToken($token);
        $user->resetPassword($form->password);
        $this->users->save($user);
    }
}