<?php

namespace core\useCases\auth;


use core\access\Rbac;
use core\entities\User\User;
use core\forms\auth\SignupForm;
use core\repositories\UserRepository;
use core\services\Mailer;
use core\services\RoleManager;
use core\services\TransactionManager;

class SignupService
{
    private $users;
    private $roles;
    private $transaction;

    public function __construct(
        UserRepository $users,
        RoleManager $roles,
        TransactionManager $transaction
    )
    {
        $this->users = $users;
        $this->roles = $roles;
        $this->transaction = $transaction;
    }

    public function signup(SignupForm $form): User
    {
        $user = User::requestSignup(
            $form->email,
            $form->password,
            $form->type
        );
        $this->transaction->wrap(function () use ($user) {
            $this->users->save($user);
            $this->roles->assign($user->id, Rbac::ROLE_USER);
            if ($user->status == User::STATUS_WAIT) {
                Mailer::send(
                    $user->email,
                    \Yii::$app->name . ': Подтверждение регистрации',
                    'auth/confirm',
                    ['user' => $user]
                );
            }
        });
        return $user;
    }

    public function confirm($token): void
    {
        if (empty($token)) {
            throw new \DomainException('Пустой токен.');
        }
        $user = $this->users->getByEmailConfirmToken($token);
        $user->confirmSignup();
        $this->users->save($user);
    }
}