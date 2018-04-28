<?php

namespace core\useCases\manage;


use core\entities\User\User;
use core\forms\manage\User\UserCreateForm;
use core\forms\manage\User\UserEditForm;
use core\repositories\UserRepository;
use core\services\Mailer;
use core\services\RoleManager;
use core\services\TransactionManager;

class UserManageService
{
    private $repository;
    private $roles;
    private $transaction;

    public function __construct(
        UserRepository $repository,
        RoleManager $roles,
        TransactionManager $transaction
    )
    {
        $this->repository = $repository;
        $this->roles = $roles;
        $this->transaction = $transaction;
    }

    public function create(UserCreateForm $form): User
    {
        $user = User::create(
            $form->email,
            $form->password,
            $form->type
        );
        $this->transaction->wrap(function () use ($user, $form) {
            $this->repository->save($user);
            $this->roles->assign($user->id, $form->role);
        });
        return $user;
    }

    public function edit($id, UserEditForm $form): void
    {
        $user = $this->repository->get($id);
        $user->edit(
            $form->email,
            $form->password,
            $form->type
        );
        $this->transaction->wrap(function () use ($user, $form) {
            $this->repository->save($user);
            $this->roles->assign($user->id, $form->role);
        });
    }

    public function updateStatus(User $user, $status): void
    {
        $oldStatus = $user->status;
        $user->updateStatus($status);
        $this->repository->save($user);

        if ($user->status == User::STATUS_ACTIVE && $oldStatus < User::STATUS_ACTIVE) {
            Mailer::send(
                $user->email,
                \Yii::$app->params['siteName'] . ': Ваш профиль активирован',
                'auth/user-activated',
                ['user' => $user]
            );
        }
    }

    public function assignRole($id, $role): void
    {
        $user = $this->repository->get($id);
        $this->roles->assign($user->id, $role);
    }

    public function remove($id): void
    {
        $user = $this->repository->get($id);
        $this->repository->remove($user);
    }
}