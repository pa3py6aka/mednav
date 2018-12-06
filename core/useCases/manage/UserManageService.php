<?php

namespace core\useCases\manage;


use core\components\Settings;
use core\entities\User\User;
use core\forms\manage\User\UserCreateForm;
use core\forms\manage\User\UserEditForm;
use core\jobs\SendMailJob;
use core\repositories\UserRepository;
use core\services\Mailer;
use core\services\RoleManager;
use core\services\TransactionManager;
use Yii;

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
            Yii::$app->queue->push(new SendMailJob([
                'to' => $user->email,
                'subject' => '[' . Yii::$app->settings->get(Settings::GENERAL_EMAIL_FROM) . '] Ваш профиль активирован',
                'view' => 'auth/user-activated',
                'params' => ['user' => $user],
            ]));
        }
    }

    public function assignRole($id, $role): void
    {
        $user = $this->repository->get($id);
        $this->roles->assign($user->id, $role);
    }

    public function activate($ids): void
    {
        if (!\is_array($ids)) {
            $ids = [$ids];
        }
        foreach ($ids as $id) {
            $user = $this->repository->get($id);
            $this->updateStatus($user, User::STATUS_ACTIVE);
            $this->repository->save($user);
        }
    }

    public function massRemove(array $ids, $hardRemove = false): int
    {
        return $this->repository->massRemove($ids, $hardRemove);
    }

    public function remove($id, $safe = true): void
    {
        $user = $this->repository->get($id);
        if ($safe) {
            $this->repository->safeRemove($user);
        } else {
            $this->repository->remove($user);
        }
    }
}